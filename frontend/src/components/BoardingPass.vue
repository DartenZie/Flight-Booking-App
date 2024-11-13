<script setup lang="ts">
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faPlaneDeparture} from "@fortawesome/free-solid-svg-icons";
</script>

<template>
    <div class="boarding-pass grid grid-cols-[2fr_1fr] grid-rows-[4rem_16rem]">
        <!-- TOP Background -->
        <div class="col-start-1 col-end-3 row-start-1 row-end-1 bg-blue-600"></div>

        <div class="col-start-1 col-end-2 row-start-1 row-end-1 flex justify-center items-center">
            <h4 class="text-white font-normal text-xl uppercase tracking-widest">Boarding Pass</h4>
        </div>

        <div class="col-start-2 col-end-2 row-start-1 row-end-1 flex justify-center items-center">
            <h4 class="text-white font-light text-lg uppercase tracking-wide">RyanAir</h4>
        </div>

        <div class="relative col-start-1 col-end-2 row-start-1 row-span-2">
            <!-- Divider -->
            <div class="absolute w-0.5 h-full right-0 bg-vertical-dashed bg-right bg-[length:0.5rem_2rem]"></div>
        </div>

        <div class="col-start-1 col-end-2 row-start-2 row-span-1 bg-world bg-contain bg-center bg-no-repeat">
            <div class="px-8 py-3 h-full grid grid-cols-[2fr_4fr_2fr] grid-rows-[3fr_1fr] gap-x-8 gap-y-4">
                <div class="col-span-1 overflow-hidden">
                    <div class="text-gray-700 text-md font-medium uppercase mb-1.5">From:</div>
                    <div class="text-blue-700 text-4xl font-bold">PRG</div>
                    <div class="text-blue-700 text-sm font-light uppercase text-nowrap overflow-hidden overflow-ellipsis mb-3">Prague</div>
                    <div class="text-gray-700 font-light -mb-1">Jan 25, 2025</div>
                    <div class="text-gray-700 font-light">05:50 PM</div>
                </div>
                <div class="col-span-1 flex justify-center items-center">
                    <font-awesome-icon class="text-blue-600 text-6xl opacity-55" :icon="faPlaneDeparture" />
                </div>
                <div class="col-span-1 overflow-hidden">
                    <div class="text-gray-700 text-md font-medium uppercase mb-1.5">To:</div>
                    <div class="text-blue-700 text-4xl font-bold">CPH</div>
                    <div class="text-blue-700 text-sm font-light uppercase text-nowrap overflow-hidden overflow-ellipsis mb-3">Copenhagen</div>
                    <div class="text-gray-700 font-light -mb-1">Jan 25, 2025</div>
                    <div class="text-gray-700 font-light">07:10 PM</div>
                </div>

                <div class="col-start-1 col-span-3 row-start-2 border-t-2 border-slate-400">
                    <div class="grid grid-cols-5 gap-x-3 pt-3">
                        <div class="col-span-1">
                            <div class="text-gray-600 text-xs uppercase">Passenger</div>
                            <div class="text-blue-700 text-md font-medium text-nowrap overflow-hidden overflow-ellipsis">John Doe</div>
                        </div>
                        <div class="col-span-1">
                            <div class="text-gray-600 text-xs uppercase">Flight</div>
                            <div class="text-blue-700 text-md font-medium text-nowrap overflow-hidden overflow-ellipsis">FR 0123</div>
                        </div>
                        <div class="col-span-1">
                            <div class="text-gray-600 text-xs uppercase">Terminal</div>
                            <div class="text-blue-700 text-md font-medium text-nowrap overflow-hidden overflow-ellipsis">2B</div>
                        </div>
                        <div class="col-span-1">
                            <div class="text-gray-600 text-xs uppercase">Gate</div>
                            <div class="text-blue-700 text-md font-medium text-nowrap overflow-hidden overflow-ellipsis">C23</div>
                        </div>
                        <div class="col-span-1">
                            <div class="text-gray-600 text-xs uppercase">Seat</div>
                            <div class="text-blue-700 text-md font-medium text-nowrap overflow-hidden overflow-ellipsis">12F</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-start-2 col-span-1 px-8 py-3 h-full">
            <div class="grid grid-rows-[1fr_1fr_2fr] gap-y-3 h-full">
                <div class="row-span-1 border-b-2 border-slate-400 flex justify-between">
                    <div class="max-w-1/2">
                        <div class="text-gray-600 text-xs uppercase">Passenger</div>
                        <div class="text-blue-700 text-md font-medium text-nowrap overflow-hidden overflow-ellipsis">John Doe</div>
                    </div>
                    <div class="max-w-1/2">
                        <div class="text-gray-600 text-xs uppercase">Class</div>
                        <div class="text-blue-700 text-md font-medium text-nowrap overflow-hidden overflow-ellipsis">Business</div>
                    </div>
                </div>
                <div class="row-span-1 border-b-2 border-slate-400 grid grid-cols-3 gap-x-3 items-center">
                    <div class="col-span-1">
                        <div class="text-gray-600 text-xs uppercase">Date</div>
                        <div class="text-blue-700 text-xs font-medium text-nowrap overflow-hidden overflow-ellipsis">JAN 25</div>
                    </div>
                    <div class="col-span-1">
                        <div class="text-gray-600 text-xs uppercase">Boarding</div>
                        <div class="text-blue-700 text-xs font-medium text-nowrap overflow-hidden overflow-ellipsis">05:20 PM</div>
                    </div>
                    <div class="col-span-1">
                        <div class="text-gray-600 text-xs uppercase">Depart</div>
                        <div class="text-blue-700 text-xs font-medium text-nowrap overflow-hidden overflow-ellipsis">05:50 PM</div>
                    </div>
                </div>
                <div class="row-span-1">
                    <img src="../assets/images/barcode.gif" alt="ABC-abc-1234" class="mt-3">
                </div>
            </div>
        </div>
    </div>
</template>
