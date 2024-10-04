@php
    $cardLogo = \App\Models\Utility::get_file('card_logo');
    $profile = \App\Models\Utility::get_file('uploads/avatar');
@endphp

<div class="row">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-10 col-xxl-12 col-md-12">
            <div class="p-3 card ">
                <ul class="nav nav-pills nav-fill information-tab" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active show" id="business-setting-tab" data-bs-toggle="pill"
                            data-bs-target="#business-setting" type="button">{{ __('Business') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="user-setting-tab" data-bs-toggle="pill"
                            data-bs-target="#user-setting" type="button">{{ __('User') }}</button>
                    </li>

                </ul>
            </div>
            <div class="px-0 card-body">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="business-setting" role="tabpanel"
                        aria-labelledby="pills-user-tab-1">
                        <div class="tab-pane text-capitalize fade show " role="tabpanel">
                            <div class="row workspace">
                                <div class="col-4 text-center">
                                    <h5 class="text-muted">{{ __('Total Business') }}</h5>
                                    <p class="text-muted text-md mb-0" data-toggle="tooltip"
                                        data-bs-original-title="{{ __('Total Business') }}"><i
                                            class="ti ti-users text-warning card-icon-text-space  mx-1"></i><span
                                            class="total_business">{{ $totalBusiness }}</span>

                                    </p>
                                </div>
                                <div class="col-4 text-center">
                                    <h5 class="text-muted">{{ __('Enable Business') }}</h5>
                                    <p class="text-muted text-md mb-0" data-toggle="tooltip"
                                        data-bs-original-title="{{ __('Enable Business') }}"><i
                                            class="ti ti-users text-primary card-icon-text-space  mx-1"></i><span
                                            class="enable_business">{{ $totalBusinessEnable }}</span>
                                    </p>
                                </div>
                                <div class="col-4 text-center">
                                    <h5 class="text-muted">{{ __('Disable Business') }}</h5>
                                    <p class="text-muted text-md mb-0" data-toggle="tooltip"
                                        data-bs-original-title="{{ __('Disable Business') }}"><i
                                            class="ti ti-users text-danger card-icon-text-space  mx-1"></i><span
                                            class="disable_business">{{ $totalBusinessDisable }}</span>
                                    </p>
                                </div>
                            </div>
                            <hr>

                            <div class="row my-2 ">
                                @foreach ($businessDetails as $businessDetail)
                                    <div class="col-md-6 my-2 ">
                                        <div
                                            class="d-flex align-items-center justify-content-between list_colume_notifi pb-2">
                                            <div class="mb-3 mb-sm-0">
                                                <h6>
                                                    <img style="width: 30px;height: 30px;" class="rounded-circle "
                                                        src="{{ isset($businessDetail->logo) && !empty($businessDetail->logo) ? $cardLogo . '/' . $businessDetail->logo : asset('custom/img/logo-placeholder-image-21.png') }}"
                                                        alt="">
                                                    <a href="{{ url('/' . $businessDetail->slug) }}" target="_blank"
                                                        class="{{ $businessDetail->admin_enable == 'off' ? 'row-disabled' : '' }}"
                                                        id="link_{{ $businessDetail->id }}">
                                                        <label for="user"
                                                            class="form-label">{{ $businessDetail->title }}</label>
                                                    </a>
                                                </h6>
                                            </div>
                                            <div class="text-end ">
                                                <div class="form-check form-switch custom-switch-v1 mb-2">
                                                    <input type="checkbox" name="user_disable"
                                                        class="form-check-input input-primary is_disable" value="1"
                                                        data-id='{{ $businessDetail->id }}'
                                                        data-name="{{ __('business') }}"
                                                        {{ $businessDetail->admin_enable == 'on' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="user_disable"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="user-setting" role="tabpanel" aria-labelledby="pills-user-tab-1">
                        <div class="tab-pane text-capitalize fade show " role="tabpanel">
                            <div class="row workspace">
                                <div class="col-4 text-center">
                                    <h5 class="text-muted">{{ __('Total User') }}</h5>
                                    <p class="text-muted text-md mb-0" data-toggle="tooltip"
                                        data-bs-original-title="{{ __('Total User') }}"><i
                                            class="ti ti-users text-warning card-icon-text-space  mx-1"></i><span
                                            class="total_user">{{ $totalUser }}</span>
                                    </p>
                                </div>
                                <div class="col-4 text-center">
                                    <h5 class="text-muted">{{ __('Enable User') }}</h5>
                                    <p class="text-muted text-md mb-0" data-toggle="tooltip"
                                        data-bs-original-title="{{ __('Enable User') }}"><i
                                            class="ti ti-users text-primary card-icon-text-space  mx-1"></i><span
                                            class="enable_user">{{ $totalUserEnable }}</span>
                                    </p>
                                </div>
                                <div class="col-4 text-center">
                                    <h5 class="text-muted">{{ __('Disable User') }}</h5>
                                    <p class="text-muted text-md mb-0" data-toggle="tooltip"
                                        data-bs-original-title="{{ __('Disable User') }}"><i
                                            class="ti ti-users text-danger card-icon-text-space  mx-1"></i><span
                                            class="disable_user">{{ $totalUserDisable }}</span>
                                    </p>
                                </div>
                            </div>
                            <hr>

                            <div class="row my-2 ">
                                @foreach ($userDetails as $userDetail)
                                    <div class="col-md-6 my-2 ">
                                        <div
                                            class="d-flex align-items-center justify-content-between list_colume_notifi pb-2">
                                            <div class="mb-3 mb-sm-0">
                                                <h6>
                                                    <img style="width: 30px;height: 30px;" class="rounded-circle "
                                                        src="{{ isset($userDetail->avatar) && !empty($userDetail->avatar) ? $profile . '/' . $userDetail->avatar : asset('custom/img/logo-placeholder-image-21.png') }}"
                                                        alt="">
                                                    <a href="" target="_blank"
                                                        class="{{ $userDetail->admin_enable == 'off' ? 'row-disabled' : '' }}"
                                                        id="user_link_{{ $userDetail->id }}">
                                                        <label for="user"
                                                            class="form-label">{{ $userDetail->name }}</label>
                                                    </a>
                                                </h6>
                                            </div>
                                            <div class="text-end ">
                                                <div class="form-check form-switch custom-switch-v1 mb-2">
                                                    <input type="checkbox" name="user_disable"
                                                        class="form-check-input input-primary is_disable_user"
                                                        value="1" data-id='{{ $userDetail->id }}'
                                                        data-name="{{ __('user') }}"
                                                        {{ $userDetail->admin_enable == 'on' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="user_disable"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on("click", ".is_disable", function() {
        var id = $(this).attr('data-id');
        var is_disable = ($(this).is(':checked')) ? $(this).val() : 0;

        $.ajax({
            url: '{{ route('business.unable') }}',
            type: 'POST',
            data: {
                "is_disable": is_disable,
                "id": id,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('.total_business').text(data.totalBusiness);
                $('.enable_business').text(data.totalBusinessEnable);
                $('.disable_business').text(data.totalBusinessDisable);
                if (is_disable == 0) {
                    $('#link_' + id).addClass('row-disabled');
                } else {
                    $('#link_' + id).removeClass('row-disabled');
                }
                if (data.is_success == true) {
                    toastrs('{{ __('Success') }}', data.msg, 'success');
                }
            }
        });
    });
    $(document).on("click", ".is_disable_user", function() {
        var id = $(this).attr('data-id');

        var is_disable_user = ($(this).is(':checked')) ? $(this).val() : 0;


        $.ajax({
            url: '{{ route('user.unable') }}',
            type: 'POST',
            data: {
                "is_disable_user": is_disable_user,
                "id": id,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('.total_user').text(data.totalUser);
                $('.enable_user').text(data.totalUserEnable);
                $('.disable_user').text(data.totalUserDisable);
                if (is_disable_user == 0) {
                    $('#user_link_' + id).addClass('row-disabled');
                } else {
                    $('#user_link_' + id).removeClass('row-disabled');
                }
                if (data.is_success == true) {
                    toastrs('{{ __('Success') }}', data.msg, 'success');
                }
            }
        });
    });
</script>
