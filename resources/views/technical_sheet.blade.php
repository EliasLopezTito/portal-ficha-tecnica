@extends('layouts.technicalSheet')

@section('title', $property['address'] . ', ' . ucfirst(strtolower($property['district']['name'])) . ', ' .
    ucfirst(strtolower($property['department']['name'])))

@section('content')

    <section x-data='property(@json($property), @json(count($property['resources'])))' class="container-technicalSheet">
        <div class="lightGallery-property-images row overflow-hidden position-relative">
            <div class="col-12 col-lg-6 col-xl-5">
                <div class="lightGallery-property-images__image lightGallery-property-images__image-feature rounded-2 cursor-pointer"
                    data-src="{{ $AWS_URL_S3 }}/{{ $property['featured_image'] }}"
                    style="background-image: url('{{ $AWS_URL_S3 }}/{{ $property['featured_image'] }}')">
                    <img class="d-none" src="{{ $AWS_URL_S3 }}/{{ $property['featured_image'] }}">
                </div>
            </div>
            <div class="d-none d-lg-block col-lg-6 col-xl-7">
                <div class="row g-3">
                    @foreach ($property['resources'] as $index => $resource)
                        <div class="col-lg-12 col-xl-6 col-xxl-4">
                            <div class="lightGallery-property-images__image lightGallery-property-images__image-resource rounded-2 cursor-pointer"
                                data-src="{{ $AWS_URL_S3 }}/{{ $resource['resource_url'] }}"
                                style="background-image: url('{{ $AWS_URL_S3 }}/{{ $resource['resource_url'] }}')">
                                <img class="d-none" src="{{ $AWS_URL_S3 }}/{{ $resource['resource_url'] }}"
                                    alt="">

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <button class="btn z-2 p-0 border-0 rounded-circle text-black fw-bolder position-absolute end-0 bottom-0 fs-3"
                disabled
                style="background-color: rgba(255, 255, 255, 0.9) !important; width: 55px; height: 55px; margin: 0 30px 15px 0;"
                x-text="buttonText" :class="imagesMin ? 'd-none' : 'd-block'">
            </button>
        </div>
        <div class="property-information py-4">
            <div class="property-images__info text-white px-3 px-md-4 py-3 py-md-4 mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3 gap-md-5">
                    <div>
                        <div class="d-flex gap-2 mb-3">
                            <span class="badge rounded-pill fs-2 px-3 d-flex align-items-center text-bg-dark">
                                @if ($property['operation_id'] === 1)
                                    En Venta
                                @elseif ($property['operation_id'] === 2)
                                    En Alquiler
                                @endif
                            </span>
                            <span class="badge rounded-pill fs-2 px-3 d-flex align-items-center text-bg-primary">
                                Destacado
                            </span>
                            <span
                                class="badge rounded-pill fs-2 px-3 d-flex align-items-center text-bg-light cursor-pointer">
                                <img class="me-1" src="{{ asset('assets/images/icons/fotos.svg') }}" width="14"
                                    alt="imagen">
                                {{ count($property['resources']) + 1 }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <h1 class="fs-7 fw-bolder d-inline-block text-dark me-3 mb-3 mb-md-0">{{ $property['title'] }}
                            </h1>
                            <div class="d-inline-block">
                                @if ($property['prices'][0]['code'])

                                    @php
                                        $prices = collect($property['prices']);
                                        $mainCurrency = $prices->where('code', $property['prices'][0]['code'])->first();
                                        $otherCurrency = $prices
                                            ->where('code', '!=', $property['prices'][0]['code'])
                                            ->where('pivot.price', '>', 0)
                                            ->first();
                                    @endphp
                                    <span class="badge fw-bolder rounded-pill fs-6 px-3 text-bg-primary me-2 mb-2 mb-sm-0">
                                        {{ $mainCurrency['symbol'] }}
                                        {{ number_format($mainCurrency['pivot']['price'], 2, '.', ',') }}
                                    </span>
                                    @if ($otherCurrency)
                                        <span class="badge fw-bolder rounded-pill fs-6 px-3 text-bg-primary">
                                            {{ $otherCurrency['symbol'] }}
                                            {{ number_format($otherCurrency['pivot']['price'], 2, '.', ',') }}
                                        </span>
                                    @endif
                                @else
                                    <span class="badge fw-bolder rounded-pill fs-6 px-3 text-bg-primary">
                                        {{ $property['prices']->first()['symbol'] }}
                                        {{ number_format($property['prices']->first()['pivot']['price'], 2, '.', ',') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="ti ti-map-pin text-primary fs-7 me-2 d-none d-sm-block"></i>
                            <span class="text-dark fs-3">
                                {{ $property['address'] }} - {{ ucfirst(strtolower($property['district']['name'])) }} -
                                {{ ucfirst(strtolower($property['department']['name'])) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column gap-4">

                <div class="d-flex flex-column gap-3 gap-md-4">
                    {{-- Titulo --}}
                    <div class="d-flex flex-column flex-md-row align-items-md-center">
                        <h4 class="fw-bolder pe-4 m-0 border-end fs-6 mb-md-0 mb-2 property-information__title">
                            Descripción general
                        </h4>
                        <div class="d-flex align-items-center px-md-4">
                            <span class="fs-3 fw-bolder text-muted me-3">
                                Identificación de la propiedad: {{ $property['correlative_code'] }}
                            </span>
                            <span class="badge rounded-pill fs-1 px-3 d-flex align-items-center text-bg-primary">
                                Destacado
                            </span>
                        </div>
                    </div>
                    {{-- Características --}}
                    <div class="d-flex flex-wrap gap-2">
                        @if (isset($features['principal']))
                            @foreach ($features['principal'] as $item)
                                @php
                                    $icon = [
                                        'Dormitorios' => 'ti ti-bed',
                                        'Baños' => 'ti ti-bath',
                                        'Medios Baños' => 'ti ti-bath',
                                        'Número de Pisos' => 'ti ti-stairs',
                                        'Cocheras' => 'ti ti-car-garage',
                                    ];
                                @endphp
                                <div class="bg-primary-subtle px-3 py-2 rounded-2 gap-2">
                                    <span style="white-space: nowrap;"
                                        class="d-block fw-bolder mb-1 fs-2">{{ $item['feature']['name'] }}</span>
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-{{ $icon[$item['feature']['name']] }} fs-5 text-primary me-2"></i>
                                        <span class="fw-bold fs-2">{{ $item['value'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        @if ($property['condition_id'] === 3 && $property['age'] !== null)
                            <div class="bg-primary-subtle px-4 py-2 rounded-2 gap-2">
                                <span style="white-space: nowrap;" class="d-block fs-2 fw-bolder mb-1">Año de
                                    construcción</span>
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-calendar-month fs-5 text-primary me-2"></i>
                                    <span class="fw-bolder fs-2">{{ date('Y') - $property->age }}</span>
                                </div>
                            </div>
                        @endif
                        <div class="bg-primary-subtle px-4 py-2 rounded-2 gap-2">
                            <span style="white-space: nowrap;" class="d-block fw-bolder fs-2 mb-1">Área construida</span>
                            <div class="d-flex align-items-center">
                                <i class="ti ti-ruler fs-5 text-primary me-2"></i>
                                <span class="fw-bolder fs-2 me-1">{{ $property['built_area'] }}</span>
                                <span>m<sup>2</sup>
                                </span>
                            </div>
                        </div>
                        <div class="bg-primary-subtle px-4 py-2 rounded-2 gap-2">
                            <span style="white-space: nowrap;" class="d-block fw-bolder fs-2 mb-1">Área total</span>
                            <div class="d-flex align-items-center">
                                <i class="ti ti-ruler fs-5 text-primary me-2"></i>
                                <span class="fw-bolder fs-2 me-1">{{ $property['total_area'] }}</span>
                                <span>m<sup>2</sup>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Descripción --}}
                <div class="property-description d-flex flex-column gap-3 gap-md-4">
                    <h4 class="property-description__title fw-bolder fs-6 m-0">Descripción</h4>
                    <div class="property-description__data"></div>
                </div>
                {{-- Características --}}
                @php
                    $otherKeysExist = count(array_diff_key($features, ['principal' => ''])) > 0;
                @endphp
                @if (!empty($features) && $otherKeysExist)
                    <div class="d-flex flex-column gap-3 gap-md-4">
                        <h4 class="property-description__title fw-bolder fs-6 m-0">Características</h4>
                        <div>
                            <ul class="nav nav-underline" id="tabFeatures" role="tablist">
                                @php
                                    $counter = 1;
                                @endphp
                                @foreach ($features as $key => $group)
                                    @if ($key === 'principal')
                                        @continue
                                    @endif
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link fs-3 {{ $counter === 1 ? 'active' : '' }}"
                                            id="{{ $key }}-tab" data-bs-toggle="tab" href="#{{ $key }}"
                                            role="tab" aria-controls="{{ $key }}"
                                            @if ($counter === 1) aria-expanded="true" @endif
                                            aria-selected=" {{ $counter === 1 ? 'active' : 'false' }} ">
                                            <span>{{ $group[0]->feature->group_name }}</span>
                                        </a>
                                    </li>
                                    @php
                                        $counter++;
                                    @endphp
                                @endforeach
                            </ul>
                            <div class="tab-content tabcontent-border px-2 py-4" id="tabFeaturesContent">
                                @php
                                    $counter2 = 1;
                                @endphp
                                @foreach ($features as $key => $group)
                                    @if ($key === 'principal')
                                        @continue
                                    @endif
                                    <div role="tabpanel" class="tab-pane fade {{ $counter2 === 1 ? 'active show' : '' }}"
                                        id="{{ $key }}" aria-labelledby="{{ $key }}-tab">
                                        <div class="d-flex flex-wrap gap-4">
                                            @foreach ($group as $key => $item)
                                                <div class="d-flex align-items-center">
                                                    <i
                                                        class="ti ti-circle-dashed-check fw-bolder text-primary fs-5 me-1"></i>
                                                    <span>{{ $item->feature->name }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @php
                                        $counter2++;
                                    @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                {{-- Mapa --}}
                <div class="d-flex flex-column gap-3">
                    <h4 class="fw-bolder fs-6 mb-0">
                        Ubicación
                    </h4>
                    <div class="d-block d-sm-flex align-items-center justify-content-between">
                        <div class="mb-2 mb-sm-0">
                            <i class="ti ti-map-pin text-primary fs-4 me-2"></i>
                            <span class="text-black fw-bold fs-3">
                                {{ $property['address'] }} - {{ ucfirst(strtolower($property['district']['name'])) }} -
                                {{ ucfirst(strtolower($property['department']['name'])) }}
                            </span>
                        </div>
                        <div>
                            <a href="https://www.google.com/maps?q={{ $property['latitude'] }},{{ $property['longitude'] }}"
                                target="_blank" class="btn btn-outline-success">
                                Abrir en Google Maps
                                <i class="ti ti-map-pin fs-5"></i>
                            </a>
                        </div>
                    </div>
                    <div class="rounded-4 border" id="property-location-map" style="height: 400px">
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center align-items-center m-auto">

            <div class="d-flex align-items-center py-5">
                <div class="me-2">
                    <span class="text-muted fw-bold fs-2">Desarrollado por Portal Cielo</span>
                </div>
                <img src="{{ asset('assets/images/logos/isotipo_negro.png') }}" width="25" alt="Logo">
            </div>
        </div>
    </section>
@endsection
