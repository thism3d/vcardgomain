@extends('layouts.admin')
@section('page-title')
    {{ __('Business Analytics') }}
@endsection
@section('title')
    {{ __('Business Analytics') }}
@endsection
@section('breadcrumb')
    @if (Auth::user()->type == 'company')
        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('business.index') }}">{{ __('Business') }}</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">{{ __('Business Analytics') }}</li>
    @else
        <li class="breadcrumb-item active" aria-current="page"><a
                href="{{ route('campaigns.index') }}">{{ __('Campaigns') }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ __('Business Analytics') }}</li>
    @endif
@endsection
@section('action-btn')
    <div class="col-xl-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-between justify-content-md-end"
        data-bs-placement="top">
        <a href="#filter_analytics" class="btn btn-sm btn-primary   " id="toggle-filter-btn">
            <div class="float-end"><i class="ti ti-filter"></i></div>
        </a>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12 {{ $isFiltered ? '' : 'd-none' }}" id="filter_analytics">
            <div class=" mt-2 " id="multiCollapseExample1" style="">
                <div class="card">
                    <div class="card-body ana">
                        {{ Form::open(['route' => ['business.analytics', $id], 'method' => 'get', 'id' => 'analytics_filter']) }}
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                                <div class="btn-box">
                                    {{ Form::label('start_date', __('Start date'), ['class' => 'form-label']) }}
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ isset($_GET['start_date']) ? $_GET['start_date'] : '' }}"
                                        placeholder ="">
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                                <div class="btn-box">
                                    {{ Form::label('end_date', __('End date'), ['class' => 'form-label']) }}
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ isset($_GET['end_date']) ? $_GET['end_date'] : '' }}" placeholder ="">
                                </div>
                            </div>

                            <div class="col-auto float-end ms-2 mt-4">
                                <a href="#" class="btn btn-sm btn-primary"
                                    onclick="document.getElementById('analytics_filter').submit(); return false;"
                                    data-bs-toggle="tooltip" title="" data-bs-original-title="apply">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                                <a href="{{ route('business.analytics', $id) }}" class="btn btn-sm btn-danger"
                                    data-bs-toggle="tooltip" title="" data-bs-original-title="Reset">
                                    <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                </a>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-end">
                            <span class="mb-0 float-right">{{ __('Last 15 Days') }}</span>
                        </div>
                        <h5>{{ __('Appointments') }}</h5>
                    </div>
                    <div class="card-body">
                        <div id="apex-storedashborad" data-color="primary" data-height="280"></div>
                        <h6>{{ __('Promotion Data Over Time') }}</h6>
                        <small>{{ __('This chart shows the promotion periods along with the annotation of the promotion periods based on the start and end dates.') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-end">
                            <span class="mb-0 text-sm float-right mt-1">{{ __('Last 15 Days') }}</span>
                        </div>
                        <h5 class="mb-0 float-left">{{ __('Platform') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div id="user_platform-chart"></div>
                            <h6>{{ __('Platform Data with Promotion Periods') }}</h6>
                            <small>{{ __('This bar chart displays the platform data along with annotations indicating the total
                                                                                        days of promotion periods.') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <div class="float-end">
                            <span class="mb-0 text-sm float-right mt-1">{{ __('Last 15 Days') }}</span>
                        </div>
                        <h5 class="mb-0 float-left">{{ __('Device Analytics') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div id="pie-storedashborad"></div>
                            </div>
                            <div class="col-md-6">
                                <div id="pie-promotion"></div>

                            </div>

                        </div>
                        <div class="mt-4">
                            <h6>{{ __('Promotion Budget Distribution') }}</h6>
                            <small>{{ __('This pie chart shows the distribution of the budget across different promotion
                                                                                    periods.') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <div class="float-end">
                            <span class="mb-0 text-sm float-right mt-1">{{ __('Last 15 Days') }}</span>
                        </div>
                        <h5 class="mb-0 float-left">{{ __('Browser Analytics') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div id="pie-storebrowser"></div>
                            </div>
                            <div class="col-md-6">
                                <div id="pie-promotion-period"></div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h6>{{ __('Promotion Duration Distribution') }}</h6>
                            <small>{{ __('This pie chart shows the distribution of the total days for different promotion
                                                                                periods.') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style ">
                        <h5></h5>
                        <div class="table-responsive">
                            <table class="table" id="pc-dt-simple">
                                <thead>
                                    <th>{{ __('#') }} </th>
                                    <th>{{ __('Start date') }} </th>
                                    <th>{{ __('End Date') }} </th>
                                    <th>{{ __('Total Days') }} </th>
                                    <th>{{ __('Total Amount') }} </th>
                                    <th>{{ __('Payment Method') }} </th>
                                    <th>{{ __('Total') }} </th>
                                </thead>
                                <tbody>
                                    @foreach ($promoteData as $promote)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $promote->start_date }}</td>
                                            <td>{{ $promote->end_date }}</td>
                                            <td>{{ $promote->total_days }}</td>
                                            <td>{{ $promote->total_cost }}</td>
                                            <td>{{ $promote->payment_method }}</td>
                                            <td>{{ $promote->total_cost * $promote->total_days }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script src="{{ asset('custom/js/purpose.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtn = document.getElementById('toggle-filter-btn');
            const filterSection = document.getElementById('filter_analytics');

            filterBtn.addEventListener('click', function(e) {
                e.preventDefault();
                filterSection.classList.toggle('d-none');
            });
        });
    </script>
    <script>
        (function() {
            var xaxisAnnotations = [];

            @foreach ($annotations as $annotation)
                var annotation = {
                    x: "{{ $annotation['startDateString'] }}",
                    x2: "{{ $annotation['endDateString'] }}",
                    fillColor: '#ffa500',
                    opacity: 0.3,
                    label: {
                        text: 'Promotion Period',
                        borderColor: '#ffa500',
                        style: {
                            color: '#fff',
                            background: '#ffa500',
                        }
                    }
                };

                xaxisAnnotations.push(annotation);
            @endforeach

            var annotationsConfig = {
                xaxis: xaxisAnnotations
            };


            var options = {
                chart: {
                    height: 350,
                    type: 'line',
                    toolbar: {
                        show: false,
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                series: {!! json_encode($chartData['data']) !!},
                xaxis: {
                    labels: {
                        format: "MMM",
                        style: {
                            colors: PurposeStyle.colors.gray[600],
                            fontSize: "14px",
                            fontFamily: PurposeStyle.fonts.base,
                            cssClass: "apexcharts-xaxis-label"
                        }
                    },
                    axisBorder: {
                        show: !1
                    },
                    axisTicks: {
                        show: !0,
                        borderType: "solid",
                        color: PurposeStyle.colors.gray[300],
                        height: 6,
                        offsetX: 0,
                        offsetY: 0
                    },
                    type: "text",
                    categories: {!! json_encode($chartData['label']) !!}
                },
                yaxis: {
                    labels: {
                        style: {
                            color: PurposeStyle.colors.gray[600],
                            fontSize: "12px",
                            fontFamily: PurposeStyle.fonts.base
                        }
                    },
                    axisBorder: {
                        show: !1
                    },
                    axisTicks: {
                        show: !0,
                        borderType: "solid",
                        color: PurposeStyle.colors.gray[300],
                        height: 6,
                        offsetX: 0,
                        offsetY: 0
                    }
                },

                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    floating: true,
                    offsetY: -25,
                    offsetX: -5
                },
                annotations: annotationsConfig
            };
            var chart = new ApexCharts(document.querySelector("#apex-storedashborad"), options);
            chart.render();
        })();
        var options = {
            chart: {
                height: 200,
                type: 'donut',
            },
            dataLabels: {
                enabled: true,
            },
            series: {!! json_encode($devicearray['data']) !!},
            colors: ["#CECECE", '#ffa21d', '#FF3A6E', '#3ec9d6'],
            labels: {!! json_encode($devicearray['label']) !!},
            legend: {
                show: true,
                position: 'bottom',
            },
        };
        var chart = new ApexCharts(document.querySelector("#pie-storedashborad"), options);
        chart.render();
        var options = {
            chart: {
                height: 200,
                type: 'donut',
            },
            dataLabels: {
                enabled: true,
            },
            series: {!! json_encode($browserarray['data']) !!},
            colors: ["#CECECE", '#ffa21d', '#FF3A6E', '#3ec9d6'],
            labels: {!! json_encode($browserarray['label']) !!},
            legend: {
                show: true,
                position: 'bottom',
            },
        };
        var chart = new ApexCharts(document.querySelector("#pie-storebrowser"), options);
        chart.render();
    </script>
    <script>
        var WorkedHoursChart = (function() {
            var $chart = $('#user_platform-chart');
            var annotations = [
                @foreach ($annotations as $annotation)
                    {
                        y: "{{ $annotation['days'] }}",
                        borderColor: "#ffa500",
                        label: {
                            borderColor: "#ffa500",
                            style: {
                                color: "#fff",
                                background: "#ffa500"
                            },
                            text: "Promotion Period[Total Day]"
                        }
                    },
                @endforeach
            ];

            function init($this) {
                var options = {
                    chart: {
                        height: 350,
                        type: 'bar',
                        zoom: {
                            enabled: false
                        },
                        toolbar: {
                            show: false
                        },
                        shadow: {
                            enabled: false,
                        }
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '30%',
                            borderRadius: 10,
                            dataLabels: {
                                position: 'top',
                            },
                        }
                    },
                    stroke: {
                        show: true,
                        width: 1,
                        colors: ['#fff']
                    },
                    series: [{
                        name: 'Platform',
                        data: {!! json_encode($platformarray['data']) !!}
                    }],
                    xaxis: {
                        labels: {
                            style: {
                                colors: PurposeStyle.colors.gray[600],
                                fontSize: '14px',
                                fontFamily: PurposeStyle.fonts.base,
                                cssClass: 'apexcharts-xaxis-label',
                            },
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: true,
                            borderType: 'solid',
                            color: PurposeStyle.colors.gray[300],
                            height: 6,
                            offsetX: 0,
                            offsetY: 0
                        },
                        title: {
                            text: '{{ __('Platform') }}'
                        },
                        categories: {!! json_encode($platformarray['label']) !!}
                    },
                    yaxis: {
                        labels: {
                            style: {
                                color: PurposeStyle.colors.gray[600],
                                fontSize: '12px',
                                fontFamily: PurposeStyle.fonts.base,
                            },
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: true,
                            borderType: 'solid',
                            color: PurposeStyle.colors.gray[300],
                            height: 6,
                            offsetX: 0,
                            offsetY: 0
                        }
                    },
                    fill: {
                        type: 'solid',
                        opacity: 1
                    },
                    markers: {
                        size: 4,
                        opacity: 0.7,
                        strokeColor: "#fff",
                        strokeWidth: 3,
                        hover: {
                            size: 7,
                        }
                    },
                    grid: {
                        borderColor: PurposeStyle.colors.gray[300],
                        strokeDashArray: 5,
                    },
                    dataLabels: {
                        enabled: false
                    },
                    // Add annotations for promotion periods
                    annotations: {
                        yaxis: annotations
                    }
                };

                // Initialize the chart
                var chart = new ApexCharts($this[0], options);

                // Render the chart
                chart.render();
            }

            // Initialize charts
            if ($chart.length) {
                $chart.each(function() {
                    init($(this));
                });
            }
        })();
    </script>
    <script>
        var options = {
            chart: {
                height: 200,
                type: 'donut',
            },
            dataLabels: {
                enabled: true,
            },
            series: {!! json_encode($promotionData['data']) !!},
            colors: ["#CECECE", '#ffa21d', '#FF3A6E', '#3ec9d6'],
            labels: {!! json_encode($promotionData['label']) !!},
            legend: {
                show: true,
                position: 'bottom',
            },
        };
        var chart = new ApexCharts(document.querySelector("#pie-promotion"), options);
        chart.render();
        var promotionPeriodOptions = {
            chart: {
                height: 200,
                type: 'donut',
            },
            dataLabels: {
                enabled: true,
            },
            series: {!! json_encode($promotionPeriodData['data']) !!},
            colors: ["#00E396", '#008FFB', '#FEB019', '#FF4560'],
            labels: {!! json_encode($promotionPeriodData['label']) !!},
            legend: {
                show: true,
                position: 'bottom',
            },
        };
        var promotionPeriodChart = new ApexCharts(document.querySelector("#pie-promotion-period"), promotionPeriodOptions);
        promotionPeriodChart.render();
    </script>
@endpush
