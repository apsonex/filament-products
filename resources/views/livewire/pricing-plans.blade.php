<div
    class="w-full"
    x-cloak
    x-data="{
        isAnnual: true,
    }"
>
    @if ($showHeader)
        <div class="max-w-2xl mx-auto mb-10 sm:text-center">
            <p class="text-xl font-medium tracking-tight text-blue-700">Affordable Pricing, Unlimited Potential</p>
            <h2 class="text-3xl font-medium tracking-tight text-gray-900">Flexible Plans for Every Writer</h2>
            <p class="mt-2 text-lg text-gray-600">Find the perfect plan to suit your needs. AI writing awaits you.</p>
        </div>
    @endif


    <!-- Pricing toggle -->
    <div class="flex justify-center max-w-[14rem] mx-auto mb-8">
        <div class="relative flex w-full max-w-3xl p-1 bg-gray-100 border rounded-full shadow-lg dark:bg-slate-900">
            <span
                class="absolute inset-0 m-1 pointer-events-none"
                aria-hidden="true"
            >
                <span
                    class="absolute inset-0 w-1/2 transition-transform duration-150 ease-in-out transform rounded-full shadow-sm bg-primary-500 shadow-blue-950/10"
                    :class="isAnnual ? 'translate-x-0' : 'translate-x-full'"
                ></span>
            </span>
            <button
                type="button"
                class="relative flex-1 h-8 text-sm font-semibold transition-colors duration-150 ease-in-out rounded-full focus-visible:outline-none focus-visible:ring focus-visible:ring-blue-300 dark:focus-visible:ring-slate-600"
                :class="isAnnual ? 'text-white' : 'text-slate-500 dark:text-slate-400'"
                @click="isAnnual = true"
                :aria-pressed="isAnnual"
            >Yearly
                {{-- <span :class="isAnnual ? 'text-blue-200' : 'text-slate-400 dark:text-slate-500'"> - 20%</span> --}}
            </button>
            <button
                type="button"
                class="relative flex-1 h-8 text-sm font-semibold transition-colors duration-150 ease-in-out rounded-full focus-visible:outline-none focus-visible:ring focus-visible:ring-blue-300 dark:focus-visible:ring-slate-600"
                :class="isAnnual ? 'text-slate-500 dark:text-slate-400' : 'text-white'"
                @click="isAnnual = false"
                :aria-pressed="isAnnual"
            >Monthly</button>
        </div>
    </div>

    <div
        class="flex justify-center w-full mx-auto lg:max-w-screen-xl"
        x-cloak
    >
        <div class="flex flex-wrap justify-center w-full mx-auto">
            @foreach ($this->plans->groupBy('name') as $group => $plans)
                @foreach ($plans as $plan)
                    <div
                        class="w-full max-w-md px-4 py-4 md:max-w-none md:w-1/2 lg:w-1/3"
                        x-show="isAnnual ? ('{{ $plan['frequency'] }}' === 'year') : ('{{ $plan['frequency'] }}' === 'month')"
                    >
                        <div
                            class="relative w-full h-full p-6 bg-white border rounded-lg shadow-lg hover:border-primary-500 hover:shadow-primary-200">

                            @if ($plan['is_popular'] ?? false)
                                <span
                                    class="absolute top-0 px-4 py-0.5 -mt-3.5 font-semibold text-white bg-purple-500 rounded-full right-4"
                                >Popular</span>
                            @endif

                            <h3 class="text-lg font-bold text-gray-900 whitespace-nowrap">{{ $plan['label'] }}</h3>
                            <p class="mt-2 text-sm text-gray-600">{{ $plan['description'] }}</p>

                            <div class="mt-4">
                                <div class="flex items-end justify-normal whitespace-nowrap">
                                    <span class="text-lg text-gray-500 leading-0">$</span>
                                    <span
                                        class="mx-1 text-3xl font-bold text-gray-900 leading-0">{{ number_format($plan['price'] / 100, 2) }}</span>
                                    <span
                                        class="text-gray-500 leading-0"
                                        x-show="{{ $plan['price'] }} > 0"
                                    >/month</span>
                                    <span
                                        class="ml-2 font-semibold text-green-500 leading-0"
                                        x-show="isAnnual && {{ $plan['price'] }} > 0"
                                    >Save {{ $plan['percent_save'] }}%</span>
                                </div>
                            </div>

                            <div class="mt-8">
                                @if ($onPlanSelection)
                                    <button
                                        type="button"
                                        x-on:click.prevent="{{ $onPlanSelection }}('{{ $plan['slug'] }}')"
                                        class="w-full px-4 py-4 font-semibold text-center text-white transition-all duration-150 ease-in-out rounded-lg bg-primary-500 hover:bg-primary-600"
                                    >Subscribe</button>
                                @else
                                    <a
                                        {{-- href="{{ route('wizards.signup', ['planId' => $plan['slug']]) }}" --}}
                                        href="{{ route(config('filament-products.routes.purchase-subscription'), ['plan_id' => $plan['slug']]) }}"
                                        class="inline-block w-full px-4 py-4 font-semibold text-center text-white transition-all duration-150 ease-in-out rounded-lg bg-primary-500 hover:bg-primary-600"
                                    >Subscribe</a>
                                @endif
                            </div>

                            <!-- Features -->
                            <div class="pt-6 mt-6 border-t">
                                <ul class="flex flex-col space-y-3">
                                    @foreach (collect($plan['features'])->sortBy('sort_order') as $feature)
                                        @if(!isset($feature['hide']))
                                            <li
                                                class="flex items-center justify-start text-gray-600"
                                                @if (isset($feature['frequency']) && $feature['frequency'] === 'month') x-show="isAnnual === false"
                                                @elseif(isset($feature['frequency']) && $feature['frequency'] === 'year')
                                                    x-show="isAnnual === true" @endif
                                            >
                                                <x-filament-products::icon.check class="w-5 h-5 mr-2 text-green-500" />
                                                {{ $feature['label'] }}
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>

                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</div>
