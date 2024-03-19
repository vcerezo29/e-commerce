<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Navbar from '../Components/Navbar.vue';
import PrimaryButton from '../Components/PrimaryButton.vue';
import { computed, onMounted, ref, watch } from 'vue';


const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
    products: {
        type: Array,
        required: true
    },
    status: {
        type: String
    },
    cart: {
        type: Array,
        required: true
    },
    subtotal: {
        type: Number,
        required: true
    }
});

const searchQuery = ref('');
const target = ref(null);
const isCartOpen = ref(false);
const message = ref('');

const handleButtonHover = (productId) => {
    target.value = productId; // Set the value of the target to the selected product
}
const handleMouseExit = () => {
    target.value = null; // Reset the target
}
const handleButtonClick = (productId) => {
    const form = useForm({
        product: productId // Set the productId in the form data
    });

    form.post(route('cart.addproduct'), { // Submit the form data
        onSuccess: (data) => { // Handle success response
            message.value = data.props.status; // Set message value to status from response
        },
        onError: (err) => { // Handle error response
            console.log(err); // Log  the error message data to console
        }
    });
}

const handleCart = () => {
    isCartOpen.value = !isCartOpen.value; // Toggle the value of isCartOpen
}

const handeRemoveProductInCart = (id) => {
    const form = useForm({});

    form.delete(route('cart.deleteproduct', { id }), { // Send DELETE request to route with product ID
        onSuccess: (data) => { // Handle success response
            message.value = data.props.status; // Set message value to status from response
        },
        onError: (err) => { // Handle error response
            console.log(err); // Log  the error message data to console
        }
    });
}

const filteredProducts = computed(() => {
    const query = searchQuery.value.toLowerCase(); // Get the search query value and convert to lowercase
    return props.products.data.filter(product => { // Filter products based on search query
        return product.name.toLowerCase().includes(query); // Check if product name includes the search query
    });
});

const handleSearch = (newQuery) => {
    searchQuery.value = newQuery; // Set the search query value to the newQuery
};

// for the status message
watch(message, (newVal) => {
    if (newVal !== null) { // Check if message has changed and is not null
        setTimeout(() => {
            message.value = null; // Reset message value to null after 1500 milliseconds (1.5 seconds)
        }, 1500);
    }
});
</script>

<template>

    <Head title="Welcome" />
    <Navbar :handleCartOpen="handleCart"  @updateSearchQuery="handleSearch" />
    <div class="mx-auto max-w-4xl h-full p-2">
        <div class="h-3/4  mt-5">
            <div class="mx-auto max-w-2xl">
                <div v-if="message"
                    class="mb-4 fixed z-10 rounded bg-green-600 w-26 p-2 text-center font-medium text-sm text-white        ">
                    {{ message }}
                </div>
            </div>
            <div class="mx-auto max-w-2xl mt-5">
                <h1 class="text-center text-4xl">Products</h1>
                <div class="mt-6 grid grid-cols- gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                    <div v-for="product in filteredProducts" :key="product.id" class="group relative"
                        @mouseenter="handleButtonHover(product.id)" @mouseleave="handleMouseExit">
                        <div :class="target === product.id ? 'opacity-100 ' : 'opacity-0'"
                            class="z-10 absolute flex flex-col inset-0 items-center justify-center bg-blue-500 text-white px-3 py-1 rounded-md transition duration-300 ease-in-out hover:opacity-100 focus:outline-none">
                            <div class="flex">
                                <PrimaryButton @click="handleButtonClick(product.id)">Add to cart</PrimaryButton>
                            </div>
                            <div class="flex mt-2">
                                <PrimaryButton>View Product</PrimaryButton>
                            </div>
                        </div>
                        <div
                            class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
                            <img :src="product.image_path !== null ? 'storage/' + product.image_path : 'storage/imagePlaceholder.jfif'"
                                :alt="product.imageAlt"
                                class="h-full w-full object-cover object-center lg:h-full lg:w-full" />

                        </div>
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700">
                                    <a :href="product.href">
                                        <span aria-hidden="true" class="absolute inset-0" />
                                        {{ product.name }}
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">Stock {{ product.quantity-product.cart_count }}</p>
                            </div>
                            <p class="text-sm font-medium text-gray-900">₱{{ product.price }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <nav class="flex justify-center" aria-label="Pagination">
                        <ul class="inline-flex items-center">
                            <li class="mr-3" v-if="products.prev_page_url">
                                <Link :href="products.prev_page_url" class="px-4 py-2 border rounded">Previous</Link>
                            </li>
                            <li class="mr-3" v-else>
                                <button class="px-4 py-2 border rounded opacity-50 cursor-not-allowed" disabled>Previous</button>
                            </li>
                            <li class="mr-3" v-if="products.next_page_url">
                                <Link :href="products.next_page_url" class="px-4 py-2 border rounded">Next</Link>
                            </li>
                            <li v-else>
                                <button class="px-4 py-2 border rounded opacity-50 cursor-not-allowed" disabled>Next</button>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div v-if="isCartOpen" class="relative z-40 " aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div class="pointer-events-auto w-screen max-w-md">
                        <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                            <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                                <div class="flex items-start justify-between">
                                    <h2 class="text-lg font-medium text-gray-900" id="slide-over-title">Shopping cart
                                    </h2>
                                    <div class="ml-3 flex h-7 items-center">
                                        <button @click="handleCart"
                                            class="relative -m-2 p-2 text-gray-400 hover:text-gray-500">
                                            <span class="absolute -inset-0.5"></span>
                                            <span class="sr-only">Close panel</span>
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-8">
                                    <div class="flow-root">
                                        <ul class="-my-6 divide-y divide-gray-200">
                                            <li class="flex py-6" v-for="(c, index) in cart" :key="index">
                                                <div
                                                    class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                                    <img :src="'storage/' + c[0].product.image_path"
                                                        alt="Salmon orange fabric pouch with match zipper, gray zipper pull, and adjustable hip belt."
                                                        class="h-full w-full object-cover object-center">
                                                </div>

                                                <div class="ml-4 flex flex-1 flex-col">
                                                    <div>
                                                        <div
                                                            class="flex justify-between text-base font-medium text-gray-900">
                                                            <h3>
                                                                <a href="#">{{ c[0].product.name }}</a>
                                                            </h3>
                                                            <p class="ml-4">₱{{ c[0].product.price * c.length }}</p>
                                                        </div>
                                                        <p class="mt-1 text-sm text-gray-500">₱{{c[0].product.price}}/item</p>
                                                    </div>
                                                    <div class="flex flex-1 items-end justify-between text-sm">
                                                        <p class="text-gray-500">Qty {{ c.length }}</p>

                                                        <div class="flex">
                                                            <button @click="handeRemoveProductInCart(c[0].id)"
                                                                class="font-medium text-indigo-600 hover:text-indigo-500">Remove</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
                                <div class="flex justify-between text-base font-medium text-gray-900">
                                    <p>Subtotal</p>
                                    <p>₱{{ subtotal }}</p>
                                </div>
                                <p class="mt-0.5 text-sm text-gray-500">Shipping and taxes calculated at checkout.</p>
                                <div class="mt-6">
                                    <a href="#"
                                        class="flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700">Checkout</a>
                                </div>
                                <div class="mt-6 flex justify-center text-center text-sm text-gray-500">
                                    <p>
                                        or
                                        <button @click="handleCart"
                                            class="font-medium text-indigo-600 hover:text-indigo-500">
                                            Continue Shopping
                                            <span aria-hidden="true"> &rarr;</span>
                                        </button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>