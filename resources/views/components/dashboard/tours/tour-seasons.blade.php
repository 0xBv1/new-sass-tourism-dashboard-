@props([
    'tour' => null,
    'season' => false,
])
<a href="javascript:;" data-name="season" class="add-new-variant text-center mb-4 btn btn-outline-primary w-100">
    <i class="fa fa-plus"></i> Add season
</a>
@if ($season)
@foreach ($tour->seasons as $season)
<div class="row color-picks">
        <!-- Season Title -->
        <div class="col-12 mb-3">
            <x-dashboard.form.input-text
                error-key="season.{{ $loop->index }}.title"
                name="season[{{ $loop->index }}][title]"
                id="season-{{ $loop->index }}-title"
                label-title="Season Title"
                :value="$season->title ?? 'Season ' . $loop->iteration"
            />
        </div>
 @php
    $Week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $months = [
                        'January',
                        'February',
                        'March',
                        'April',
                        'May',
                        'June',
                        'July',
                        'August',
                        'September',
                        'October',
                        'November',
                        'December',
                    ];
                    $Days = $season->available['Days'] ?? [];
                $Months = $season->available['Month'] ?? [];
                $Weeks = $season->available['Week'] ?? [];
                  $days = range(1, 31);
                  $Years = $season->available['Year'] ?? [];

@endphp

    <div class="row">
        <div id="days" class="border border-dark p-0" style="font-size:13px; width:40%;">
            <label for="select-days-{{$loop->index}}"> Select All</label>
            <input type="checkbox" @if (count($Days) == count($days) )
            checked
        @endif id="select-days-{{$loop->index}}" name="select-days" onchange="Check(this)" >
            <h4>Days</h4>


            <div class="row">

                @for ($i = 1; $i <= 31; $i++)
                    @php
                        $value = false;
                    @endphp
                    @if (array_key_exists($i, $Days))
                        @php
                            $value = true;
                        @endphp
                    @endif
                    <div class="col-3">
                        <x-dashboard.form.input-checkbox :value="$value" class="col-xl-5 col-md-5"
                            resourceDesc="" error-key="avalilabe"
                            name="season[{{ $loop->index }}][available][Days][{{ $i }}]"
                            id="day{{ $loop->index }}-{{ $i }}" label-title="{{ $i }}" />
                    </div>
                @endfor
            </div>

        </div>

        <div id="month-week-year" class="d-flex flex-column mb-3 ms-2 w-50">


            <div class="border border-dark p-2 mb-2">
                <h4>Years</h4>
                @php
                    $now = \Carbon\Carbon::now();
                    $year=$now->year;
                    $nextYear = $now->copy()->addYears(1)->year;
                    $nextYear_2 = $now->copy()->addYears(2)->year;
                    $nextYear_3 = $now->copy()->addYears(3)->year;
                @endphp
                @php
                    $value1=false;
                    $value2=false;
                    $value3=false;
                    $value4=false;
                    if(array_key_exists($year, $Years)){
                        $value1=true; }

                    if(array_key_exists($nextYear, $Years)){
                        $value2=true; }

                    if(array_key_exists($nextYear_2, $Years)){
                        $value3=true; }

                    if(array_key_exists($nextYear_3, $Years)){
                        $value4=true; }


                @endphp
                <div class="row">
                    <x-dashboard.form.input-checkbox resourceDesc="" class="col-xl-3 col-md-3" error-key="avalilabe" name="season[{{ $loop->index }}][available][Year][{{$year}}]" id="year{{ $loop->index }}-{{$year}}-1" label-title="{{$year}}"  :value="$value1"/>

                    <x-dashboard.form.input-checkbox resourceDesc="" class="col-xl-3 col-md-3" error-key="avalilabe" name="season[{{ $loop->index }}][available][Year][{{$nextYear}}]" id="year{{ $loop->index }}-{{$nextYear}}-1" label-title="{{$nextYear}}" :value="$value2" />

                    <x-dashboard.form.input-checkbox resourceDesc="" class="col-xl-3 col-md-3" error-key="avalilabe" name="season[{{ $loop->index }}][available][Year][{{$nextYear_2}}]" id="year{{ $loop->index }}-{{$nextYear_2}}-1" label-title="{{$nextYear_2}}" :value="$value3" />

                    <x-dashboard.form.input-checkbox resourceDesc="" class="col-xl-3 col-md-3" error-key="avalilabe" name="season[{{ $loop->index }}][available][Year][{{$nextYear_3}}]" id="year{{ $loop->index }}-{{$nextYear_3}}-1" label-title="{{$nextYear_3}}" :value="$value4" />
                </div>
            </div>


            <div id="week" class="border border-dark mb-2 px-2">
                <label for="select-weeks-{{$loop->index}}"> Select All</label>
                <input type="checkbox" @if (count($Week) == count($Weeks) )
                checked
            @endif id="select-weeks-{{$loop->index}}" name="select-weeks" onchange="Check(this)" >

                <h4>Dyas Of the Week</h4>

                <div class="row">
                    @foreach ($Week as $i => $day)
                        @php
                            $value = false;
                        @endphp
                        @if (array_key_exists($day, $Weeks))
                            @php
                                $value = true;
                            @endphp
                        @endif
                        <div class="col-6">

                            <x-dashboard.form.input-checkbox class="col-xl-6 col-md-6" resourceDesc=""
                                error-key="avalilabe" name="season[{{ $loop->parent->index }}][available][Week][{{ $day }}]"
                                id="{{ $day }}{{ $loop->parent->index}}-1" label-title="{{ $day }}"
                                :value="$value" />
                        </div>
                    @endforeach
                </div>
            </div>
            <div id="month" class="border border-dark p-2 mb-2">
                <label for="select-months-{{$loop->index}}"> Select All</label>
                <input type="checkbox"  @if (count($Months) == count($months) )
                    checked
                @endif id="select-months-{{$loop->index}}" name="select-months" onchange="Check(this)" >

                <h4>Months</h4>
                @php

                @endphp
                <div class="row">
                    @foreach ($months as $i => $m)
                        @php
                            $value = false;
                        @endphp
                        @if (array_key_exists($m, $Months))
                            @php
                                $value = true;
                            @endphp
                        @endif
                        <div class="col-6">
                            <x-dashboard.form.input-checkbox class="col-xl-6 col-md-6" resourceDesc=""
                                error-key="avalilabe" name="season[{{ $loop->parent->index }}][available][Month][{{ $m }}]"
                                id="{{ $m }}{{ $loop->parent->index }}-1" label-title="{{ $m }}"
                                :value="$value" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div>


            <a href="javascript:;" data-name="season[{{ $loop->index }}][pricing_groups]"
                class="add-first-variant-season text-center mb-4 btn btn-outline-primary w-100">
                <i class="fa fa-plus"></i> Add Group Prcing
            </a>
            @foreach ($season->pricing_groups as $pricing_group)
                <div class="row color-picks season">
                    <x-dashboard.form.input-number
                        error-key="season.{{ $loop->parent->index }}.{{ $loop->index }}.from"
                        name="season[{{ $loop->parent->index }}][pricing_groups][{{ $loop->index }}][from]"
                        :id="'seasons-' . $loop->parent->index . '-from-' . $loop->index" label-title="From" :value="intval($pricing_group['from'])" />

                    <x-dashboard.form.input-number
                        error-key="season.{{ $loop->parent->index }}.{{ $loop->index }}.to"
                        name="season[{{ $loop->parent->index }}][pricing_groups][{{ $loop->index }}][to]"
                        :id="'seasons-' . $loop->parent->index . '-to-' . $loop->index" label-title="To" :value="intval($pricing_group['to'])" />

                    <x-dashboard.form.input-text
                        error-key="season.{{ $loop->parent->index }}.{{ $loop->index }}.price"
                        name="season[{{ $loop->parent->index }}][pricing_groups][{{ $loop->index }}][price]"
                        id="seasons-{{ $loop->parent->index }}-price-{{ $loop->index }}" label-title="Adult Price"
                        :value="floatval($pricing_group['price'])" />

                    <x-dashboard.form.input-text
                        error-key="season.{{ $loop->parent->index }}.{{ $loop->index }}.price"
                        name="season[{{ $loop->parent->index }}][pricing_groups][{{ $loop->index }}][child_price]"
                        id="seasons-{{ $loop->parent->index }}-price-{{ $loop->index }}" label-title="Child Price"
                        :value="floatval($pricing_group['child_price'])" />




                    @if ($loop->iteration > 1)
                        <a href="javascript:;"
                            class="remove-variant text-center mb-4 btn btn-outline-danger w-100">
                            <i class="fa fa-trash"></i> Remove Group Pricing
                        </a>
                    @endif
                    <br>
                </div>
            @endforeach
        </div>
        @if ($loop->iteration > 1)
            <a href="javascript:;" class="remove-variant text-center mb-4 btn btn-outline-danger w-100">
                <i class="fa fa-trash"></i> Remove season
            </a>
        @endif
        <hr>
    </div>
</div> <!-- Close the .row.color-picks div here -->
@endforeach

@else
    <div class="row color-picks">
                <div class="col-12 mb-3">
                    <x-dashboard.form.input-text
                        error-key="season.0.title"
                        name="season[0][title]"
                        id="season-0-title"
                        label-title="Season Title"
                        :value="'Season 1'"
                    />
                </div>
        @php
            $months = [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December',
            ];
            $days = range(1, 31);
        @endphp
        <div class="row">
            <div id="days-year" class="col-12">
                <div class="row">
                    <div id="days" class="col-md-6 border border-dark pt-3" style="font-size:13px;">
                        <label for="select-days-1"> Select All</label>
                        <input type="checkbox" id="select-days-1" name="select-days" onchange="Check(this)" >
                        <h4>Days</h4>
                        <div class="row">
                            @for ($i = 1; $i <= 31; $i++)
                                <div class="col-3">
                                    <x-dashboard.form.input-checkbox class="col-xl-5 col-md-5" resourceDesc=""
                                        error-key="avalilabe" name="season[0][available][Days][{{ $i }}]"
                                        id="day-{{ $i }}-1" label-title="{{ $i }}" />
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border border-dark p-2 mb-2">
                        <h4>Years</h4>
                        @php
                            $now = \Carbon\Carbon::now();
                            $year=$now->year;
                            $nextYear = $now->copy()->addYears(1)->year;
                            $nextYear_2 = $now->copy()->addYears(2)->year;
                            $nextYear_3 = $now->copy()->addYears(3)->year;
                        @endphp
                        <div class="row">
                            <x-dashboard.form.input-checkbox resourceDesc="" class="col-xl-3 col-md-3" error-key="avalilabe" name="season[0][available][Year][{{$year}}]" id="{{$year}}-1" label-title="{{$year}}" />

                            <x-dashboard.form.input-checkbox resourceDesc="" class="col-xl-3 col-md-3" error-key="avalilabe" name="season[0][available][Year][{{$nextYear}}]" id="{{$nextYear}}-1" label-title="{{$nextYear}}" />

                            <x-dashboard.form.input-checkbox resourceDesc="" class="col-xl-3 col-md-3" error-key="avalilabe" name="season[0][available][Year][{{$nextYear_2}}]" id="{{$nextYear_2}}-1" label-title="{{$nextYear_2}}" />

                            <x-dashboard.form.input-checkbox resourceDesc="" class="col-xl-3 col-md-3" error-key="avalilabe" name="season[0][available][Year][{{$nextYear_3}}]" id="{{$nextYear_3}}-1" label-title="{{$nextYear_3}}" />
                        </div>
                    </div>
                        <div id="week" class="border border-dark mb-2 p-3 px-2 ">
                            <label for="select-weeks-1"> Select All</label>
                            <input type="checkbox" id="select-weeks-1" name="select-weeks" onchange="Check(this)" >
                            <h4>Days Of the Week</h4>

                            @php
                                $Week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            @endphp
                            <div class="row">
                                @foreach ($Week as $i => $day)
                                    <div class="col-6">
                                        <x-dashboard.form.input-checkbox resourceDesc="" class="col-xl-6 col-md-6"
                                            error-key="avalilabe"
                                            name="season[0][available][Week][{{ $day }}]"
                                            id="{{ $day }}-1" label-title="{{ $day }}" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="month-week-year" class="col-md-12 d-flex flex-column mb-3 mt-3">


                <div id="month" class="border border-dark p-2 mb-2">
                    <label for="select-days-1"> Select All</label>
                    <input type="checkbox" id="select-months-1" name="select-months" onchange="Check(this)" >
                    <h4>Months</h4>
                    <div class="row">
                        @foreach ($months as $i => $m)
                            <div class="col-6">
                                <x-dashboard.form.input-checkbox resourceDesc="" class="col-xl-6 col-md-6"
                                    error-key="avalilabe" name="season[0][available][Month][{{ $m }}]"
                                    id="{{ $m }}-1" label-title="{{ $m }}" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


        <x-dashboard.form.input-checkbox resource-name="Season" error-key="enabled" name="season[0][enabled]"
        id="season-enabled-1"
        label-title="Enabled"/>
        <a href="javascript:;" data-name="season[0][pricing_groups]"
            class="add-first-variant-season text-center mb-4 btn btn-outline-primary w-100">
            <i class="fa fa-plus"></i> Add Group Prcing
        </a>
        <div class="row color-picks season">
            <x-dashboard.form.input-number error-key="season.0.season_price.0.from"
                name="season[0][pricing_groups][0][from]" :id="'season-1-from-1'" label-title="From" />

            <x-dashboard.form.input-number error-key="season.0.season_price.0.to"
                name="season[0][pricing_groups][0][to]" :id="'season-1-to-1'" label-title="To" />

            <x-dashboard.form.input-text error-key="season[0][price]"
                name="season[0][pricing_groups][0][price]"
                id="season[0][pricing_groups][0][price]-1" label-title="Adult Price" />

            <x-dashboard.form.input-text error-key="season[0][child_price]"
                name="season[0][pricing_groups][0][child_price]"
                id="season[0][pricing_groups][0][child_price]-1" label-title="Child Price" />
            <br>
        </div>
        <hr>


@endif
@push('js')
<script>
        function Check(input) {
            var parent = input.parentNode;
            var siblings = Array.from(parent.childNodes).filter(function(node) {
                return node.nodeType === 1 && node !== input;
            });
            var thirdSibling = siblings[2];
            var inputs = thirdSibling.querySelectorAll('input[type="checkbox"]');
            var isChecked = input.checked;
            inputs.forEach(function(input) {
                input.checked = isChecked;
            });
        }

</script>

@endpush
@push('js')
    <script>
        const createEditor = (selector = '.code-editor') => {
            // Use Easy Editor instead of TinyMCE
            if (typeof EasyEditor !== 'undefined') {
                try {
                    const editor = new EasyEditor(selector);
                    return editor;
                } catch (error) {
                    console.error('Easy Editor initialization error:', error);
                }
            } else {
                console.warn('Easy Editor not loaded. Please include easy-editor.js');
            }
        }
        try {
            createEditor()
        } catch (error) {
            console.error('Editor initialization error:', error);
        }
        $(document).ready(function() {
            $('.select2').select2();
            $('.add-new-variant').click(function() {
                $('.open-media').click(function() {
                    var target = $(this).data('target');
                    var name = $(this).data('name');
                    var multiple = $(this).attr('multiple');
                    $(this).attr('id', target);

                    window.payload = {
                        target: target,
                        name: name,
                        multiple: multiple,
                    };
                    console.log(target, name, window.payload, $(this));
                    window.openWindow('/file-manager/fm-button', 'fm');
                });

                $('.add-new-variant-season').click(function() {
                    let inputsGroupName = $(this).data('name')
                    let originalContainer = $(this).parent().find('.season:eq(0)')
                    let idInput = originalContainer.children().first()[0].outerHTML || ''
                    let idx = $(this).parent().children('.season').length + 1
                    let removeText = $(this).data('remove-text')
                    console.log($(this), originalContainer)
                    let element = originalContainer.html().replaceAll('-1', '-' + idx)
                        .replaceAll(`<br>`, `<a href="javascript:;" class="remove-variant text-center mb-4 btn btn-outline-danger w-100">
             <i class="fa fa-trash"></i> ${removeText || 'Remove Group Pricing'}
         </a>
         <br>`)
                        //old input qty generated html
                        .replaceAll(
                            `<div class="input-group-append"><span class="input-group-text bootstrap-touchspin-postfix" style="display: none;"></span></div>`,
                            '')
                        .replaceAll(
                            `<button class="btn btn-primary btn-square bootstrap-touchspin-down" type="button"><i class="fa fa-minus"></i></button>`,
                            '')
                        .replaceAll(
                            `<div class="input-group-append ml-2"><button class="btn btn-primary btn-square bootstrap-touchspin-up" type="button"><i class="fa fa-plus"></i></button></div>`,
                            '')
                        .replaceAll(
                            `<div class="input-group-append ml-0"><button class="btn btn-primary btn-square bootstrap-touchspin-up" type="button"><i class="fa fa-plus"></i></button></div>`,
                            '')
                        .replaceAll(`${inputsGroupName}[0]`, `${inputsGroupName}[${idx}]`)
                        .replaceAll(`days[0][tour_day_image]`, `days[${idx-1}][tour_day_image]`)
                        .replaceAll(`#days0tour_day_image`, `#days${idx-1}tour_day_image`)
                        .replaceAll(`        <input name="season[pricing_groups][0][to]" id="season-1-to-1" class="touchspin form-control" type="text"  style="display: block;">
`, `<input name="season[pricing_groups][${idx-1}]season[${idx-1}][to]" id="season-${idx-1}-to-${idx-1}" class="touchspin form-control" type="text" value="" style="display: block;">`)


                        .replaceAll(`days0tour_day_image`, `days${idx-1}tour_day_image`)
                        .replaceAll(`style="display: none;"`, '')
                        .replaceAll(`aria-hidden="true"`, '')
                    console.log($(this), originalContainer)

                    if (idInput.includes('hidden')) {
                        element = element.replaceAll(idInput, '')
                    }

                    originalContainer.parent().append(
                        `<div class="row color-picks season">${element}</div>`)
                    let recentlyCreated = originalContainer.parent().children('.season').last()
                    recentlyCreated.find('.easy-editor-container').remove()
                    recentlyCreated.find('input,textarea').val('')
                    setTimeout(() => {
                        createEditor('.code-editor')
                        // $('.code-editor').hide()
                    }, 250);
                    $('.color-box input').change(function() {
                        $(this).parent().find('span').css("background-color", $(this).val())
                    })
                    $(".touchspin").TouchSpin({
                        buttondown_class: "btn btn-primary btn-square",
                        buttonup_class: "btn btn-primary btn-square",
                        buttondown_class: "btn btn-primary btn-square",
                        buttonup_class: "btn btn-primary btn-square",
                        buttondown_txt: '<i class="fa fa-minus"></i>',
                        buttonup_txt: '<i class="fa fa-plus"></i>'
                    })
                    $('.remove-variant').on('click', function() {
                        $(this).parent().remove()
                    })
                })

            });

            function fmSetLink($url, target = null, name = null) {
                if (!window.payload.multiple) {
                    $(target).find('.card.image-box').remove();
                }

                $(target).append(`
             <div class="card image-box m-5">
                 <input type="hidden" ${name ? 'name=' + name : ''} value="${$url}">
                 <img src="${$url}" class="card-img-top" alt="...">
                 <a href="javascript:;" class="btn btn-remove btn-danger btn-sm"><i class="fa fa-trash"></i></a>
             </div>
         `);

                $(document).off('click', '.image-box .btn-remove').on('click', '.image-box .btn-remove',
                    function() {
                        $(this).parent().remove();
                    });
            }


        });
    </script>
@endpush

@push('js')
    <script>
        $('.add-first-variant-season').click(function() {
            console.log($(this))
            let inputsGroupName = $(this).data('name')
            let originalContainer = $(this).parent().find('.season:eq(0)')
            let idInput = originalContainer.children().first()[0].outerHTML || ''
            let idx = $(this).parent().children('.season').length + 1
            let removeText = $(this).data('Remove Group Pricing')
            console.log($(this), originalContainer)
            let element = originalContainer.html().replaceAll('-1', '-' + idx)
                .replaceAll(`<br>`, `<a href="javascript:;" class="remove-variant text-center mb-4 btn btn-outline-danger w-100">
             <i class="fa fa-trash"></i> ${removeText || 'Remove Group Pricing'}
         </a>
         <br>`)
                //old input qty generated html
                .replaceAll(
                    `<div class="input-group-append"><span class="input-group-text bootstrap-touchspin-postfix" style="display: none;"></span></div>`,
                    '')
                .replaceAll(
                    `<button class="btn btn-primary btn-square bootstrap-touchspin-down" type="button"><i class="fa fa-minus"></i></button>`,
                    '')
                .replaceAll(
                    `<div class="input-group-append ml-2"><button class="btn btn-primary btn-square bootstrap-touchspin-up" type="button"><i class="fa fa-plus"></i></button></div>`,
                    '')
                .replaceAll(
                    `<div class="input-group-append ml-0"><button class="btn btn-primary btn-square bootstrap-touchspin-up" type="button"><i class="fa fa-plus"></i></button></div>`,
                    '')
                .replaceAll(`${inputsGroupName}[0]`, `${inputsGroupName}[${idx}]`)
                .replaceAll(`days[0][tour_day_image]`, `days[${idx-1}][tour_day_image]`)
                .replaceAll(`#days0tour_day_image`, `#days${idx-1}tour_day_image`)
                .replaceAll(`        <inpseason[pricing_groups][0][to]" id="season-1-to-1" class="touchspin form-control" type="text"  style="display: block;">
`, `<input name="season[pricing_groups][${idx-1}]season[${idx-1}][to]" id="season-${idx-1}-to-${idx-1}" class="touchspin form-control" type="text" value="" style="display: block;">`)
            .replaceAll(`"season-0-enabled-0" `, `"season-${idx}-enabled-${idx}"`)



                .replaceAll(`days0tour_day_image`, `days${idx-1}tour_day_image`)
                .replaceAll(`style="display: none;"`, '')
                .replaceAll(`aria-hidden="true"`, '')
            console.log($(this), originalContainer)

            if (idInput.includes('hidden')) {
                element = element.replaceAll(idInput, '')
            }

            originalContainer.parent().append(`<div class="row color-picks season">${element}</div>`)
            let recentlyCreated = originalContainer.parent().children('.season').last()
            recentlyCreated.find('.easy-editor-container').remove()
            recentlyCreated.find('input,textarea').val('')
            setTimeout(() => {
                createEditor('.code-editor')
                // $('.code-editor').hide()
            }, 250);
            $('.color-box input').change(function() {
                $(this).parent().find('span').css("background-color", $(this).val())
            })
            $(".touchspin").TouchSpin({
                buttondown_class: "btn btn-primary btn-square",
                buttonup_class: "btn btn-primary btn-square",
                buttondown_class: "btn btn-primary btn-square",
                buttonup_class: "btn btn-primary btn-square",
                buttondown_txt: '<i class="fa fa-minus"></i>',
                buttonup_txt: '<i class="fa fa-plus"></i>'
            })
            $('.remove-variant').on('click', function() {
                $(this).parent().remove()
            })
        })
    </script>
@endpush
