<script setup lang="ts">
import {ref} from "vue";

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faArrowLeft, faShoePrints, faPlane, faChevronRight, faUpRightAndDownLeftFromCenter, faUser, faTimes } from '@fortawesome/free-solid-svg-icons';
import {SeatingModel} from "@/models/plane.model";

const seatingModel = ref<SeatingModel>({
    cabins: [
        {
            id: 1,
            className: 'business',
            rows: 6,
            isles: [
                {
                    id: 0,
                    seats: [
                        {
                            id: 0,
                            colCode: 'A',
                            legRoom: 74
                        }
                    ]
                },
                {
                    id: 1,
                    seats: [
                        {
                            id: 1,
                            colCode: 'B',
                            legRoom: 74
                        },
                        {
                            id: 2,
                            colCode: 'C',
                            legRoom: 74
                        },
                    ]
                },
                {
                    id: 2,
                    seats: [
                        {
                            id: 3,
                            colCode: 'D',
                            legRoom: 74
                        },
                    ]
                },
            ]
        },
        {
            id: 0,
            className: 'economy',
            rows: 12,
            moreLegRoom: [6, 7],
            isles: [
                {
                    id: 0,
                    seats: [
                        {
                            id: 5,
                            colCode: 'A',
                            legRoom: 74
                        },
                        {
                            id: 6,
                            colCode: 'B',
                            legRoom: 74
                        },
                        {
                            id: 7,
                            colCode: 'C',
                            legRoom: 74
                        },
                    ]
                },
                {
                    id: 1,
                    seats: [
                        {
                            id: 8,
                            colCode: 'D',
                            legRoom: 74
                        },
                        {
                            id: 9,
                            colCode: 'E',
                            legRoom: 74
                        },
                        {
                            id: 10,
                            colCode: 'F',
                            legRoom: 74
                        },
                    ]
                },
            ]
        },
    ],
    takenSeats: [2, 3, 4, 5, 8, 9, 11, 17, 28, 34]
});
</script>

<template>
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
                            src="../../static/companies/wizzair.png"
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
                            src="../../static/companies/ryanair.png"
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
                <div class="border-b border-gray-900/10 pb-12 flex justify-center">
                    <div>
                        <div class="mb-14">
                            <h2 class="text-base/7 font-semibold text-gray-900">Select your seats</h2>
                            <p class="mt-1 text-sm/6 text-gray-600">Choose your preferred seats for the best travel experience.</p>
                        </div>

                        <div class="flex gap-x-20 h-160">
                            <div class="w-96 h-full flex flex-col justify-between">
                                <div class="mt-4">
                                    <h4 class="text-slate-500 font-medium text-sm">Flight #1</h4>
                                    <div class="text-slate-950 font-bold text-md mb-8">Prague (PRG) - Copenhagen (CPH)</div>

                                    <div class="border border-gray-200 h-12 rounded-2xl flex items-center justify-between px-6 mb-4">
                                        <div class="flex gap-x-4 items-center">
                                            <font-awesome-icon :icon="faUser" class="text-purple-700" />
                                            <div class="font-medium text-md text-gray-500">Passenger 1</div>
                                        </div>
                                        <div class="flex gap-x-4">
                                            <div class="font-medium text-sm text-gray-500">Seat</div>
                                            <div class="font-medium text-sm w-6">--</div>
                                        </div>
                                    </div>

                                    <div class="border border-gray-200 h-12 rounded-2xl flex items-center justify-between px-6 mb-4">
                                        <div class="flex gap-x-4 items-center">
                                            <font-awesome-icon :icon="faUser" class="text-purple-700" />
                                            <div class="font-medium text-md text-gray-500">Passenger 2</div>
                                        </div>
                                        <div class="flex gap-x-4">
                                            <div class="font-medium text-sm text-gray-500">Seat</div>
                                            <div class="font-medium text-sm w-6">--</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4 flex flex-col gap-y-3">
                                    <div class="flex justify-between">
                                        <div class="flex gap-x-4 items-center">
                                            <div class="w-10 h-10 rounded flex items-center justify-center bg-gray-400">
                                                <font-awesome-icon :icon="faTimes" class="text-white" />
                                            </div>
                                            <div class="font-medium text-md">Not available</div>
                                        </div>

                                        <div><!-- --></div>
                                    </div>

                                    <div class="flex justify-between">
                                        <div class="flex gap-x-4 items-center">
                                            <div class="w-10 h-10 rounded flex items-center justify-center bg-blue-400"></div>
                                            <div class="font-medium text-md">Economy seat</div>
                                        </div>

                                        <div class="flex items-center font-medium text-gray-600">19.90 €</div>
                                    </div>

                                    <div class="flex justify-between">
                                        <div class="flex gap-x-4 items-center">
                                            <div class="w-10 h-10 rounded flex items-center justify-center bg-blue-400">
                                                <font-awesome-icon :icon="faUpRightAndDownLeftFromCenter" class="text-white" />
                                            </div>
                                            <div class="font-medium text-md">Economy seat (more space)</div>
                                        </div>

                                        <div class="flex items-center font-medium text-gray-600">29.90 €</div>
                                    </div>

                                    <div class="flex justify-between">
                                        <div class="flex gap-x-4 items-center">
                                            <div class="w-10 h-10 rounded flex items-center justify-center bg-yellow-400"></div>
                                            <div class="font-medium text-md">Business seat</div>
                                        </div>

                                        <div class="flex items-center font-medium text-gray-600">69.90 €</div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-100 rounded-lg w-fit py-6 overflow-auto">
                                <div v-for="cabin in seatingModel.cabins" :key="cabin.id" class="flex flex-col gap-y-2 mb-6">
                                    <div class="font-semibold text-center text-lg mb-4 text-gray-700">{{ cabin.className }}</div>
                                    <div class="justify-center flex gap-x-8 px-14">
                                        <div v-for="isle in cabin.isles" :key="isle.id" class="flex gap-x-2">
                                            <div v-for="seat in isle.seats" :key="seat.id">
                                                <div class="w-10 text-center font-medium">{{ seat.colCode }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-for="index in cabin.rows" :key="index" class="relative flex justify-center">
                                        <div class="absolute left-2 bottom-1/2 translate-y-1/2 w-6 font-semibold text-md text-gray-600 text-center"><span>{{ index }}</span></div>
                                        <div class="flex gap-x-8 px-14">
                                            <div v-for="isle in cabin.isles" :key="isle.id" class="flex gap-x-2">
                                                <div v-for="seat in isle.seats" :key="seat.id">
                                                    <div :class="['w-10 h-10 rounded flex items-center justify-center cursor-pointer', cabin.className === 'business' ? 'bg-yellow-400 hover:bg-yellow-500' : '', cabin.className === 'economy' ? 'bg-blue-400 hover:bg-blue-500' : '']">
                                                        <font-awesome-icon v-if="cabin.moreLegRoom?.includes(index)" :icon="faUpRightAndDownLeftFromCenter" class="text-white" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
