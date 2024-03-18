<template>
    <nav class="bg-gray-800">
        <div class="mx-auto max-w-4xl px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <div
                        class="relativve inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="absolute -inset-0 5"></span>
                        <span class="sr-only">Open main menu</span>
                    </div>
                </div>
                <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex flex-shrink-0 items-center">
                        <Link href="/" class="text-white">E-Commerce</Link>
                        <!-- <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500"
                            alt="Your Company" /> -->
                    </div>
                </div>
                <div class="max-w-md mx-auto rounded-lg overflow-hidden md:max-w-xl">
                    <div class="md:flex">
                        <div class="w-full p-2">
                            <div class="relative">
                                <i class="absolute fa fa-search text-gray-400 top-5 left-4"></i>
                                <input v-model="searchQuery" type="text" placeholder="Search products..."
                                    class="bg-white h-10 w-full px-12 rounded-lg focus:outline-none hover:cursor-pointer"
                                    name="">
                                <span class="absolute top-4 right-5 border-l pl-4"><i
                                        class="fa fa-microphone text-gray-500 hover:text-green-500 hover:cursor-pointer"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative ml-3">
                    <div>
                        <button @click="props.handleCartOpen" class="relative flex rounded-full bg-gray-800 text-sm  focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                            <span class="absolute -inset-1.5"></span>
                            <span class="sr-only">Open user menu</span>
                            <FontAwesomeIcon class="text-white" :icon="faShoppingCart" />
                        </button>
                    </div>
                    <!-- <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                        <div :class="isMenuOpen? '': 'hidden'" class=" absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                            <Link  :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">My Cart</Link>
                        </div>
                    </transition> -->
                </div>
            </div>
        </div>
        <div class="sm:hidden">
            <div class="space-y-1 px-2 pb-3 pt-2">
                <div class="space-y-1 px-2 pb-3 pt2">

                </div>
            </div>
        </div>
    </nav>
</template>
<script setup>
import { ref, watch, defineEmits } from "vue"
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faShoppingCart } from '@fortawesome/free-solid-svg-icons'
import { Link } from "@inertiajs/vue3";

const navigation = [

]
const isMenuOpen = ref(false);

const handleOpenMenu = () => {
    isMenuOpen.value = !isMenuOpen.value
}

const searchQuery = ref('');

const emits = defineEmits(['updateSearchQuery']);
watch(searchQuery, (newVal) => {
    emits('updateSearchQuery', newVal);
});

const props = defineProps({
    handleCartOpen: null
})
</script>