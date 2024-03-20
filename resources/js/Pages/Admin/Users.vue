<template>

    <Head title="Users" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Users</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="newUserForm.recentlySuccessful" class="mb-4 font-medium text-sm text-green-600">
                    {{ status }}
                </div>
                <button @click="handleOpenModal(0)"
                    class="p-2 dark:bg-slate-50 dark:text-gray-800 hover:dark:bg-slate-400 w-32 rounded mb-10 ">Add
                    New User</button>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 h-auto grid grid-rows-1 gap-4">
                        <div class="relative mt-2"> <input v-model="searchQuery" type="text"
                                class="w-full h-12 rounded dark:text-black focus:outline-none px-3 focus:shadow-md"
                                placeholder="Search..."> <i
                                class="fa fa-search absolute right-3 top-4 text-gray-300"></i>
                        </div>
                        <div class="overflow-y-auto no-scrollbar">
                            <ul class="">
                                <li v-for="(user, index) in filteredProducts" :key="index"
                                    class="flex justify-between items-center bg-white mt-2 p-2 hover:shadow-lg hover:shadow-slate-400 rounded cursor-pointer transition">
                                    <div class="flex ml-2">
                                        <!-- <img :src="user.image_path !== null ? 'storage/' + product.image_path : 'storage/imagePlaceholder.jfif'"
                                            width="40" height="40" class="rounded-full"> -->
                                        <div class="flex flex-col ml-2">
                                            <span class="font-medium text-black">#{{ index + 1 }} {{ user.name }}</span>
                                            <span class="text-sm text-gray-400 ">Email: {{ user.email }}</span>
                                            <span class="text-sm text-gray-400  w-44">Role: <span
                                                    v-for="(role, index) in user.roles" :key="index">{{ role.name
                                                    }}</span></span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col justify-center gap-2">
                                        <span @click="handleOpenModal(1,user.id)"
                                            v-if="$page.props.auth.user.id !== user.id"
                                            class="bg-slate-400 hover:bg-slate-600 text-center p-1 rounded text-gray-50">Edit</span>
                                        <span @click="handleOpenModal(2,user.id)"
                                            v-if="$page.props.auth.user.id !== user.id"
                                            class="bg-slate-400 hover:bg-slate-600 text-center p-1 rounded text-gray-50">Delete</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="isAddModal" @close="handleOpenModal()">
            <div class="p-5">
                <h1 class="text-white text-2xl">Adding New User</h1>
                <div class="mt-5">
                    <form @submit.prevent="handleAddUser">
                        <div>
                            <InputLabel for="name" value="Name" />
                            <TextInput placeholder="Enter the Name" v-model="newUserForm.name" id="name" type="text"
                                class="mt-1 block w-full" autofocus />
                            <InputError class="mt-2" :message="newUserForm.errors.name" />
                        </div>
                        <div>
                            <InputLabel for="email" value="Email Address" />
                            <TextInput placeholder="Enter Email address" v-model="newUserForm.email" id="email"
                                type="email" class="mt-1 block w-full" required autofocus />
                            <InputError class="mt-2" :message="newUserForm.errors.email" />
                        </div>
                        <div>
                            <InputLabel for="password" value="Password" />
                            <TextInput placeholder="Enter Password" v-model="newUserForm.password" id="password"
                                type="password" class="mt-1 block w-full" required autofocus />
                            <InputError class="mt-2" :message="newUserForm.errors.password" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton class="ms-4" :class="{ 'opacity-25': newUserForm.processing }"
                                :disabled="newUserForm.processing">
                                Add
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </Modal>
        <Modal :show="isEditModal" @close="handleOpenModal()">
            <div class="p-5">
                <h1 class="text-white text-2xl">Edit User</h1>
                <div class="mt-5">
                    <form @submit.prevent="handeSaveUser">
                        <div>
                            <InputLabel for="name" value="Name" />
                            <TextInput placeholder="Enter the Name" v-model="updateUserFOrm.name" id="name" type="text"
                                class="mt-1 block w-full" autofocus />
                            <InputError class="mt-2" :message="updateUserFOrm.errors.name" />
                        </div>
                        <div>
                            <InputLabel for="email" value="Email Address" />
                            <TextInput placeholder="Enter Email address" v-model="updateUserFOrm.email" id="email"
                                type="email" class="mt-1 block w-full" required autofocus />
                            <InputError class="mt-2" :message="updateUserFOrm.errors.email" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton class="ms-4" :class="{ 'opacity-25': updateUserFOrm.processing }"
                                :disabled="updateUserFOrm.processing">
                                Save
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </Modal>
        <Modal :show="isDeleteModal" @close="handleOpenModal()">
            <div class="p-5">
                <h1 class="text-white text-2xl">Deleting Product</h1>
                <div class="mt-5">
                    <form @submit.prevent="handleDeleteuser">
                        <div>
                            <h1 class="text-2xl text-white">Are you sure you want to <span
                                    class="text-red-600">delete</span>
                                this Item?</h1>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton class="ms-4" :class="{ 'opacity-25': deleteForm.processing }"
                                :disabled="deleteForm.processing">
                                Delete
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref, computed } from 'vue';
import { data } from 'autoprefixer';
const props = defineProps({
    status: {
        type: String,
    },
    users: {
        type: Array
    }
})


const searchQuery = ref('');
const isAddModal = ref(false);
const isEditModal = ref(false);
const isDeleteModal = ref(false);

const newUserForm = useForm({
    'name': '',
    'email': '',
    'password': '',
})
const updateUserFOrm = useForm({
    'id': '',
    'name': '',
    'email': ''
});

const deleteForm = useForm({
    'id': '',
})

const handleOpenModal = (type, id) => {
    if (type === 0) isAddModal.value = !isAddModal.value
    else if (type === 1) {
        const result = props.users.find(x => x.id == id);
        updateUserFOrm.id = id
        updateUserFOrm.name = result.name
        updateUserFOrm.email = result.email
        isEditModal.value = !isEditModal.value
    } else if (type === 2) {
        isDeleteModal.value = !isDeleteModal.value
        deleteForm.id = id;
    }else {
        isAddModal.value = false,
        isEditModal.value = false,
        isDeleteModal.value = false
    }
}
const handleAddUser = () => {
    newUserForm.post(route('users.add'), {
        onSuccess: (data) => {
            isAddModal.value = false
            newUserForm.reset()
        },
        onError: (err) => {
            console.log(err);
        }
    })
}
const handeSaveUser = () => {
    updateUserFOrm.put(route('users.update'), {
        onSuccess: (data) => {
            isEditModal.value = false;
            updateUserFOrm.reset();
        },
        onError: (err) => {
            console.log(err);
        }
    })
}

const handleDeleteuser = () => {
    deleteForm.delete(route('users.delete', { id: deleteForm.id }), {
        onSuccess: (data) => {
            isDeleteModal.value = false;
            updateUserFOrm.reset();
        },
        onError: (err) => {
            console.log(err);
        }
    })
}


const filteredProducts = computed(() => {
    const query = searchQuery.value.toLowerCase(); // Get the search query value and convert to lowercase
    return props.users.filter(users => { // Filter products based on search query
        return users.name.toLowerCase().includes(query); // Check if product name includes the search query
    });
});
</script>