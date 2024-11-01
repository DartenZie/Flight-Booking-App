<script setup lang="ts">
import HeaderNav from '@/components/HeaderNav.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faArrowLeft, faShoePrints, faPlane, faChevronRight } from '@fortawesome/free-solid-svg-icons';
</script>

<template>
    <header-nav />

    <div class="relative bg-copenhagen bg-cover bg-center h-160">
        <div class="absolute bottom-0 left-0 right-0 top-0 h-full w-full overflow-hidden bg-gradient-to-r from-green-100 via-green-50 to-transparent opacity-80"></div>

        <div
            class="container mx-auto h-full flex flex-auto items-center justify-start"
        >
            <p class="font-thin text-5xl leading-snug text-slate-950 z-10">
                Let's book your flight<br />
                to <span class="font-medium">Copenhagen</span>!
            </p>
        </div>
    </div>

    <div class="container mx-auto -mt-32">
        <div
            class="relative h-72 bg-white rounded-lg shadow-md px-10 pt-6 pb-12"
        >
            <h2 class="font-medium text-lg mb-8">
                <font-awesome-icon :icon="faPlane" />
                <span class="ms-3">Flight information</span>
            </h2>

            <div class="flex gap-x-4 mb-12">
                <div class="w-1/3 flex gap-x-4 items-center">
                    <div class="shrink-0">
                        <img
                            class="size-12 object-contain"
                            src="@/static/companies/wizzair.png"
                            alt="Wizz Air Logo"
                        />
                    </div>
                    <div class="text-xl font-medium text-black">
                        Wizz Air
                    </div>
                </div>

                <div class="w-1/3 flex gap-x-8 items-center ms-10">
                    <div class="w-12 flex flex-col items-end">
                        <div class="text-lg font-medium text-slate-950">12:10</div>
                        <div class="text-sm font-light text-slate-600">
                            PRG
                        </div>
                    </div>
                    <div class="border-b-2 border-dashed border-slate-950 w-28"></div>
                    <div class="w-16">
                        <div class="text-lg font-medium text-slate-950">13:30</div>
                        <div class="text-sm font-light text-slate-600">
                            CPH
                        </div>
                    </div>
                    <div class="text-lg font-normal text-slate-800">1h 20m</div>
                </div>

                <div class="w-1/3 flex items-center justify-end">
                    <div class="me-24">
                        <div class="text-right">
                            <font-awesome-icon :icon="faShoePrints" class="text-xs me-3" />
                            <span class="font-thin">Leg room: </span>
                            <span class="font-medium">74 cm</span>
                        </div>
                        <div class="text-right font-thin text-xs">
                            WizzAir &#183;  Airbus A321 Neo &#183; 5W3487
                        </div>
                    </div>
                    <div class="flex gap-x-1.5 items-center">
                        <div class="font-medium text-xl text-slate-950">39</div>
                        <div class="font-light text-sm text-slate-700">EUR</div>
                    </div>
                </div>
            </div>
            <div class="flex gap-x-4">
                <div class="w-1/3 flex gap-x-4 items-center">
                    <div class="shrink-0">
                        <img
                            class="size-12 object-contain"
                            src="@/static/companies/ryanair.png"
                            alt="RyanAir Logo"
                        />
                    </div>
                    <div class="text-xl font-medium text-black">
                        RyanAir
                    </div>
                </div>

                <div class="w-1/3 flex gap-x-8 items-center ms-10">
                    <div class="w-12 flex flex-col items-end">
                        <div class="text-lg font-medium text-slate-950">06:40</div>
                        <div class="text-sm font-light text-slate-600">
                            CPH
                        </div>
                    </div>
                    <div class="border-b-2 border-dashed border-slate-950 w-28"></div>
                    <div class="w-16">
                        <div class="text-lg font-medium text-slate-950">08:00</div>
                        <div class="text-sm font-light text-slate-600">
                            PRH
                        </div>
                    </div>
                    <div class="text-lg font-normal text-slate-800">1h 20m</div>
                </div>

                <div class="w-1/3 flex items-center justify-end">
                    <div class="me-24">
                        <div class="text-right">
                            <font-awesome-icon :icon="faShoePrints" class="text-xs me-3" />
                            <span class="font-thin">Leg room: </span>
                            <span class="font-medium">76 cm</span>
                        </div>
                        <div class="text-right font-thin text-xs">
                            RyanAir &#183;  Boeing 737 &#183; FR 7951
                        </div>
                    </div>
                    <div class="flex gap-x-1.5 items-center">
                        <div class="font-medium text-xl text-slate-950">49</div>
                        <div class="font-light text-sm text-slate-700">EUR</div>
                    </div>
                </div>
            </div>

            <router-link
                class="btn-primary absolute bottom-0 right-10 h-16 w-56 translate-y-1/2 text-left"
                to="/"
            >
                <span>Change Flights</span>
                <font-awesome-icon
                    :icon="faArrowLeft"
                    class="absolute right-6 bottom-1/2 translate-y-1/2"
                />
            </router-link>
        </div>
    </div>

    <div class="container mt-24 mb-24 mx-auto">
        <form>
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-40 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <h2 class="text-base/7 font-semibold text-gray-900">Passengers</h2>
                            <p class="mt-1 text-sm/6 text-gray-600">Please ensure that passengers' names are identical to the way they are written in their passports.</p>
                        </div>
                        <div class="sm:col-span-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-6">Passenger 1</h3>

                            <div class="flex gap-x-6 mb-6">
                                <div class="flex">
                                    <input type="radio" name="gender-group" class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" id="gender-group-1">
                                    <label for="gender-group-1" class="text-sm text-gray-900 ms-2">Male</label>
                                </div>

                                <div class="flex">
                                    <input type="radio" name="gender-group" class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" id="gender-group-2">
                                    <label for="gender-group-2" class="text-sm text-gray-900 ms-2">Female</label>
                                </div>
                            </div>

                            <div class="flex gap-x-10 mb-6">
                                <div class="w-1/2">
                                    <label for="firstName" class="block text-sm font-medium mb-2">First Name</label>
                                    <input type="text" id="firstName" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="John">
                                </div>
                                <div class="w-1/2">
                                    <label for="lastName" class="block text-sm font-medium mb-2">Last Name</label>
                                    <input type="text" id="lastName" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="Doe">
                                </div>
                            </div>

                            <div class="flex gap-x-10">
                                <div class="w-1/2">
                                    <label for="nationality" class="block text-sm font-medium mb-2">Nationality</label>
                                    <select id="nationality" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                                        <option selected value="" disabled>Select nationality</option>
                                        <option value="cz">Czech Republic</option>
                                        <option value="—————" disabled>—————</option>
                                        <option value="ge">German</option>
                                        <option value="nor">Norwegian</option>
                                        <option value="fr">French</option>
                                    </select>
                                </div>
                                <div class="w-1/2">
                                    <label for="lastName" class="block text-sm font-medium mb-2">Date of Birth</label>
                                    <input type="text" id="lastName" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="YYYY-MM-DD">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-40 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <h2 class="text-base/7 font-semibold text-gray-900">Contact information</h2>
                            <p class="mt-1 text-sm/6 text-gray-600">Contact phone number makes it easier for us to reach you if necessary.</p>
                        </div>
                        <div class="sm:col-span-4">
                            <div class="flex gap-x-10 mb-6">
                                <div class="w-1/2">
                                    <label for="firstName" class="block text-sm font-medium mb-2">Email</label>
                                    <input type="email" id="email" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="johndoe@gmail.com">
                                </div>
                                <div class="w-1/2">
                                    <label for="lastName" class="block text-sm font-medium mb-2">Confirm Email</label>
                                    <input type="email" id="confirmEmail" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="johndoe@gmail.com">
                                </div>
                            </div>

                            <div class="w-1/2 pe-5">
                                <label for="lastName" class="block text-sm font-medium mb-2">Telephone</label>

                                <div class="flex gap-x-2">
                                    <div class="w-1/6">
                                        <select id="phonePrefix" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                                            <option value="cz">Czech Republic (+420)</option>
                                            <option value="—————" disabled>—————</option>
                                            <option value="ge">German (+49)</option>
                                            <option value="nor">Norwegian (+47)</option>
                                            <option value="fr">French (+33)</option>
                                        </select>
                                    </div>
                                    <div class="w-5/6">
                                        <input type="text" id="phoneNumber" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="777888999">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button class="btn-primary h-12">
                        <span class="me-7">Continue</span>
                        <font-awesome-icon :icon="faChevronRight" />
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
