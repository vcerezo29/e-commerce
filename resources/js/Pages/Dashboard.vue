<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Modal from '../Components/Modal.vue';
import InputLabel from '../Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { computed, ref } from 'vue';

const isAddModalOpen = ref();
const isEditModalOpen = ref(false);
const isAddImageModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const searchQuery = ref('');


const handleAddModalOpen = () => {
    isAddModalOpen.value = !isAddModalOpen.value; // Toggle the value of isAddModalOpen
}
const handleDeleteModalOpen = (id) => {
    deleteForm.id = id; // Set the id in the deleteForm object
    isDeleteModalOpen.value = !isDeleteModalOpen.value; // Toggle the value of isDeleteModalOpen
}
const handleAddImageModalOpen = (id) => {
    if (isAddImageModalOpen.value === false && id !== null) { // Check if the modal is closed and id is not null
        const result = props.products.find(x => x.id == id); // Find the product with the given id
        addphotoForm.id = result.id; // Set the id in the addphotoForm object
    }

    isAddImageModalOpen.value = !isAddImageModalOpen.value; // Toggle the value of isAddImageModalOpen
}
const handleEditModalOpen = (id) => {
    if (isEditModalOpen.value === false && id !== null) { // Check if the modal is closed and id is not null
        const result = props.products.find(x => x.id == id); // Find the product with the given id
        editForm.id = result.id; // Set the id in the editForm object
        editForm.name = result.name; // Set the name in the editForm object
        editForm.price = result.price; // Set the price in the editForm object
        editForm.quantity = result.quantity; // Set the quantity in the editForm object
    }
    isEditModalOpen.value = !isEditModalOpen.value; // Toggle the value of isEditModalOpen
}

const form = useForm({
    name: '',
    price: 0,
    quantity: 0,
    image: null,
});

const editForm = useForm({
    id: 0,
    name: '',
    price: 0,
    quantity: 0,
    image: null
})

const deleteForm = useForm({
    id: null
})

const addphotoForm = useForm({
    image: null
})

const addPhoto = () => {
    addphotoForm.post(route('product.uploade', {id: addphotoForm.id}), { // Send POST request to upload image for product
        onSuccess: () => { // Handle success response
            isAddImageModalOpen.value = false; // Close the add image modal
            addphotoForm.reset(); // Reset the addphotoForm
        },
        onError: (error) => { // Handle error response
            console.log(error); // Log the error to console
        }
    });
}
const submit = () => {
    form.post(route('product.post'), { // Send POST request to submit product data
        onFinish: () => {}, // Callback for when the request finishes, regardless of success or failure
        onSuccess: () => { // Handle success response
            isAddModalOpen.value = false; // Close the add modal
            form.reset(); // Reset the form
        }
    });
}
const save = () => {
    editForm.put(route('product.update', { id: editForm.id }), { // Send PUT request to update product data
        onSuccess: () => { // Handle success response
            isEditModalOpen.value = false; // Close the edit modal
        }
    });
}

const handleDelete = () => {
    deleteForm.delete(route('product.delete', {id: deleteForm.id}), { // Send DELETE request to delete product
        onSuccess: () => { // Handle success response
            isDeleteModalOpen.value = false; // Close the delete modal
        }
    });
}

const filteredProducts = computed(() => {
    const query = searchQuery.value.toLowerCase(); // Get the search query value and convert to lowercase
    return props.products.filter(product => { // Filter products based on search query
        return product.name.toLowerCase().includes(query); // Check if product name includes the search query
    });
});

const props = defineProps({
    status: {
        type: String,
    },
    products: {
        type: Array
    }
});



</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>



        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="form.recentlySuccessful" class="mb-4 font-medium text-sm text-green-600">
                    {{ status }}
                </div>
                <div v-if="editForm.recentlySuccessful" class="mb-4 font-medium text-sm text-green-600">
                    {{ status }}
                </div>
                <div v-if="addphotoForm.recentlySuccessful" class="mb-4 font-medium text-sm text-green-600">
                    {{ status }}
                </div>
                <div v-if="deleteForm.recentlySuccessful" class="mb-4 font-medium text-sm text-green-600">
                    {{ status }}
                </div>
                <button @click="handleAddModalOpen"
                    class="p-2 dark:bg-slate-50 dark:text-gray-800 hover:dark:bg-slate-400 w-28 rounded mb-10 ">Add
                    Product</button>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 h-auto grid grid-rows-1 gap-4">
                        <div class="relative mt-2"> <input v-model="searchQuery" type="text"
                                class="w-full h-12 rounded dark:text-black focus:outline-none px-3 focus:shadow-md"
                                placeholder="Search..."> <i
                                class="fa fa-search absolute right-3 top-4 text-gray-300"></i>
                        </div>
                        <div class="overflow-y-auto no-scrollbar">
                            <ul class="">
                                <li v-for="(product, index) in filteredProducts" :key="index"
                                    class="flex justify-between items-center bg-white mt-2 p-2 hover:shadow-lg hover:shadow-slate-400 rounded cursor-pointer transition">
                                    <div class="flex ml-2">
                                        <img :src="product.image_path !== null ? 'storage/' + product.image_path : 'storage/imagePlaceholder.jfif'"
                                            width="40" height="40" class="rounded-full">
                                        <div class="flex flex-col ml-2"> <span class="font-medium text-black">#{{
                    index + 1 }} {{
                    product.name
                }}</span> <span class="text-sm text-gray-400  w-44">Price: ₱{{
                        product.price }}
                                                Quantity:
                                                {{ product.quantity - product.cart_count }}</span>
                                                
                                             </div>
                                    </div>
                                    <div class="flex flex-col justify-center gap-2">
                                        <span @click="handleEditModalOpen(product.id)"
                                            class="bg-slate-400 hover:bg-slate-600 text-center p-1 rounded text-gray-50">Edit</span>
                                        <span @click="handleAddImageModalOpen(product.id)"
                                            class="bg-slate-400 hover:bg-slate-600 text-center p-1 rounded text-gray-50">Add
                                            Photo</span>
                                        <span @click="handleDeleteModalOpen(product.id)"
                                            class="bg-slate-400 hover:bg-slate-600 text-center p-1 rounded text-gray-50">Delete</span>
                                    </div>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <Modal :show="isAddModalOpen" @close="handleAddModalOpen">
            <div class="p-5">
                <h1 class="text-white text-2xl">Adding Product</h1>
                <div class="mt-5">
                    <form @submit.prevent="submit">
                        <div>
                            <InputLabel for="prod_name" value="Product Name" />
                            <TextInput placeholder="Enter Product Name" v-model="form.name" id="prod_name" type="text"
                                class="mt-1 block w-full" autofocus />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                        <div>
                            <InputLabel for="prod_price" value="Product Price" />
                            <TextInput v-model="form.price" id="prod_price" type="number" class="mt-1 block w-full"
                                required autofocus />
                            <InputError class="mt-2" :message="form.errors.price" />
                        </div>
                        <div>
                            <InputLabel for="prod_quantity" value="Product Quantity" />
                            <TextInput v-model="form.quantity" id="prod_quantity" type="number"
                                class="mt-1 block w-full" required autofocus />
                            <InputError class="mt-2" :message="form.errors.quantity" />
                        </div>
                        <div>
                            <InputLabel for="prod_image" value="Product Image" />
                            <TextInput @input="form.image = $event.target.files[0]" id="prod_image" type="file"
                                class="mt-1 block w-full" autofocus />
                            <InputError class="mt-2" :message="form.errors.image" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing">
                                Save
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </Modal>
        <Modal :show="isEditModalOpen" @close="handleEditModalOpen">
            <div class="p-5">
                <h1 class="text-white text-2xl">Edit Product</h1>
                <div class="mt-5">
                    <form @submit.prevent="save">
                        <div>
                            <InputLabel for="eprod_name" value="Product Name" />
                            <TextInput placeholder="Enter Product Name" v-model="editForm.name" id="eprod_name"
                                type="text" class="mt-1 block w-full" required autofocus />
                            <InputError class="mt-2" :message="editForm.errors.name" />
                        </div>
                        <div>
                            <InputLabel for="eprod_price" value="Product Price" />
                            <TextInput v-model="editForm.price" id="eprod_price" type="number" class="mt-1 block w-full"
                                required autofocus />
                            <InputError class="mt-2" :message="editForm.errors.price" />
                        </div>
                        <div>
                            <InputLabel for="eprod_quantity" value="Product Quantity" />
                            <TextInput v-model="editForm.quantity" id="eprod_quantity" type="number"
                                class="mt-1 block w-full" required autofocus />
                            <InputError class="mt-2" :message="editForm.errors.quantity" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton class="ms-4" :class="{ 'opacity-25': editForm.processing }"
                                :disabled="editForm.processing">
                                Save
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </Modal>
        <Modal :show="isAddImageModalOpen" @close="handleAddImageModalOpen">
            <div class="p-5">
                <h1 class="text-white text-2xl">Edit Product</h1>
                <div class="mt-5">
                    <form @submit.prevent="addPhoto">
                        <div>
                            <InputLabel for="addprod_image" value="Product Image" />
                            <TextInput @input="addphotoForm.image = $event.target.files[0]" id="addprod_image"
                                type="file" class="mt-1 block w-full" autofocus />
                            <InputError class="mt-2" :message="addphotoForm.errors.image" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton class="ms-4" :class="{ 'opacity-25': addphotoForm.processing }"
                                :disabled="addphotoForm.processing">
                                Add
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </Modal>
        <Modal :show="isDeleteModalOpen" @close="handleDeleteModalOpen">
            <div class="p-5">
                <h1 class="text-white text-2xl">Deleting Product</h1>
                <div class="mt-5">
                    <form @submit.prevent="handleDelete">
                        <div>
                            <h1 class="text-2xl text-white">Are you sure you want to <span class="text-red-600">delete</span>  this Item?</h1>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton class="ms-4" :class="{ 'opacity-25': addphotoForm.processing }"
                                :disabled="addphotoForm.processing">
                                Delete
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </Modal>


    </AuthenticatedLayout>
</template>
