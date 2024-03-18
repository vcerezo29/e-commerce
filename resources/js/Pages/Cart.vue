<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Navbar from '../Components/Navbar.vue';
const props = defineProps({
    status: {
        type: String,
    },
});
</script>
<template>
    <Head title="My Cart" />
    <Navbar />
    <div class="mx-auto max-w-4xl h-full p-2">
        <div class="h-3/4  mt-5">
            <div class="mx-auto max-w-2xl">
                <div v-if="props.status"
                    class="mb-4 fixed z-10 rounded bg-green-600 w-26 p-2 text-center font-medium text-sm text-white        ">
                    Item was added to the cart.
                </div>
            </div>
            <div class="mx-auto max-w-2xl mt-10">
                <h1 class="text-center text-4xl">My Cart</h1>
                <div class="mt-6 grid grid-cols- gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                    
                </div>
                <!-- <div class="mt-6 grid grid-cols- gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                    <div v-for="product in products" :key="product.id" class="group relative"
                        @mouseenter="handleButtonHover(product.id)" @mouseleave="handleMouseExit">
                        <div :class="target === product.id ? 'opacity-100 ' : 'opacity-0'"
                            class="z-50 absolute flex flex-col inset-0 items-center justify-center bg-blue-500 text-white px-3 py-1 rounded-md transition duration-300 ease-in-out hover:opacity-100 focus:outline-none">
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
                                <p class="mt-1 text-sm text-gray-500">Stock {{ product.quantity }}</p>
                            </div>
                            <p class="text-sm font-medium text-gray-900">₱{{ product.price }}</p>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</template>