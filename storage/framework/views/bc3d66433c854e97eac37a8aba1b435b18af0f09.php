<?php
    $searchContent = getContent('search.content',true);
    $locations     = \App\Models\Location::orderBy('id', 'DESC')->whereHas('doctors')->get(['id','name']);
    $departments   = \App\Models\Department::orderBy('id', 'DESC')->whereHas('doctors')->get(['id','name']);
    $doctors       = \App\Models\Doctor::orderBy('id', 'DESC')->get(['id','name']);
?>
<section class="appoint-section ptb-80 bg-overlay-white bg_img" data-background="<?php echo e(getImage('assets/images/frontend/search/'. @$searchContent->data_values->image,'1600x640')); ?>">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 text-center">
                <div class="appoint-content">
                    <h2 class="title"><?php echo e(__($searchContent->data_values->heading)); ?></h2>
                    <p><?php echo e(__($searchContent->data_values->subheading)); ?></p>
                    <form class="appoint-form" action="<?php echo e(route('doctors.search')); ?>" method="get">
                        <div class="search-location form-group">
                            <div class="appoint-select">
                                <select class="chosen-select locations" name="species">
                                    <option value="" selected disabled><?php echo app('translator')->get('Species'); ?></option>
                                    <?php $__currentLoopData = $species; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $specie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($specie->id); ?>" <?php if($specie->id == request()->species): echo 'selected'; endif; ?>>
                                            <?php echo e(__($specie->name)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="search-location form-group">
                            <div class="appoint-select">
                                <?php
                                    $countries = App\Models\Country::orderBy('name','ASC')->get();
                                ?>
                                <select class="select2Single locations" name="country" id="country_id">
                                    <option disabled value="" selected><?php echo app('translator')->get('Select Country'); ?></option>
                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($country['id']); ?>" <?php if(isset($data['address']->country) && $data['address']->country == $country['id']): ?> selected <?php endif; ?> ><?php echo e($country['name']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php
                                    if(isset($data['address']->country)){
                                        $savedcountriesGet = App\Models\Country::find($data['address']->country);
                                    }else{
                                        $savedcountriesGet = App\Models\Country::find(0);
                                    }
                                ?>
                                <input type="hidden" id="country_id_abbrivation" value="<?php if(isset($savedcountriesGet) && $savedcountriesGet->iso2): ?><?php echo e($savedcountriesGet->iso2); ?><?php endif; ?>">
                            </div>
                        </div>
                        <div class="search-location form-group">
                            <div class="appoint-select">
                                <?php
                                    if(isset($data['address']->country)){
                                        $states = App\Models\States::where('country_id', $data['address']->country_id)->orderBy('name','ASC')->get();

                                    }else{
                                        $states = App\Models\States::where('country_id', 0)->orderBy('name','ASC')->get();
                                    }
                                ?>
                                <select name="state_id" class="select2Single locations" style="border: 1px solid black;" id="states">
                                    <option value="0">Select state</option>
                                    <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($state['iso2']); ?>" <?php if(isset($data['address']->state) && $data['address']->state==$state['iso2']): ?> selected <?php endif; ?> ><?php echo e($state['name']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        
                        <div class="search-location form-group">
                            <div class="appoint-select">
                                <select name="city_id" class="js-data-example-ajax form-select text--black" style="border: 1px solid black;" id="city">
                                    <?php if(isset($data['address']->city) && $data['address']->city != null && $data['address']->city != ''): ?>
                                        <option value="<?php echo e($data['address']->city); ?>" selected ><?php echo e($data['address']->city); ?></option>
                                    <?php else: ?>
                                        <option value="" selected disabled>Search City</option>
                                    <?php endif; ?>
                            </select>
                            </div>
                        </div>
                        <div class="search-location form-group">
                            <div class="appoint-select">
                                <select name="postal_code" class="zip_code_get form-select text--black" style="border: 1px solid black;" id="zip_code">
                                    <?php if(isset($data['address']->zip) && $data['address']->zip != null && $data['address']->zip != ''): ?>
                                        <option value="<?php echo e($data['address']->zip); ?>" selected ><?php echo e($data['address']->zip); ?></option>
                                    <?php else: ?>
                                        <option value="" selected disabled>select zipcode</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        
                        
                        <button type="submit" class="search-btn cmn-btn"><i class="las la-search"></i></button>
                        <button type="submit" class="search-btn cmn-btn" style="margin-left:10px;"><a href="<?php echo e(route('home')); ?>">Reset</a></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->startPush('script'); ?>
    <script>
        'use strict';
        $(document).ready(function() {
            $('.select2Single').select2();
        });

        $(".zip_code_get").each(function() {
            var $this = $(this);

            $this.select2({
                ajax: {
                    url: 'https://secure.geonames.org/postalCodeSearchJSON',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {

                        var cityText = $("#city_id option:selected").text();
                        var countryElement = $('#country_id_abbrivation').val();
                        var stateElement = $('#state_id').val();
                        var stateText = $("#state_id option:selected").text();

                        var data = {
                            postalcode_startsWith: params.term,
                            placename: cityText,
                            maxRows: 500,
                            username: 'attiqueurrehman'

                        };
                        if (stateText == null) {
                            return;
                        } else {
                            return data;
                        }
                    },
                    processResults: function(data) {
                        var options = [];
                        if (data.postalCodes.length > 0) {
                            data.postalCodes.forEach(postCode => {

                                var stateTextValue = $('#state_id option:selected').text().toLowerCase();
                                var countryElement = $('#country_id_abbrivation').val();

                                if (postCode.adminName1.toLowerCase() == stateTextValue && countryElement == postCode.countryCode) {
                                    var option = {
                                        id: postCode.postalCode,
                                        text: postCode.postalCode,
                                    };
                                    console.log(option);
                                    options.push(option);
                                }
                            });
                        } else {
                            console.log('No postCode found.');
                        }
                        return {
                            results: options
                        };
                    }
                },
                language: {
                    inputTooShort: function() {
                        return "Enter your city.";
                    }
                }
            });
        });

        $('.js-data-example-ajax').select2({
            ajax: {
                url: 'https://secure.geonames.org/searchJSON',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    $('#postal_code').html('');
                    var countryElement = $('#country_id_abbrivation').val();
                    var stateElement = $('#state_id').val();
                    var stateText = $("#state_id option:selected").val();
                    var data = {
                        name_startsWith:params.term,
                        country: countryElement,
                        featureClass:'P',
                        maxRows: 1000,
                        username: 'attiqueurrehman'
                    };
                    console.log(params);
                    if(stateText == null){
                        return;
                    }else{

                        return data;
                    }
                },
                processResults: function (data) {
                    var options = [];
                    if (data.geonames.length > 0) {
                        data.geonames.forEach(city => {
                            var featureCodes = ['PPLX', 'PPLC', 'PPL', 'PPLA', 'PPLA1', 'PPLA2', 'PPLA3', 'PPLA4', 'PPLA5'];
                            if (
                                city.adminName1.toLowerCase() === $('#state_id option:selected').text().toLowerCase() &&
                                featureCodes.includes(city.fcode) &&
                                !options.some(option => option.text.toLowerCase() === city.name.toLowerCase())
                            ) {
                                var option = {
                                    id: city.name,
                                    text: city.name,
                                };
                                options.push(option);
                            }
                        });
                    } else {
                        console.log('No city found.');
                    }
                    return {
                        results: options
                    };
                }
            },
            language: {
                inputTooShort: function () {
                    return "Enter your city.";
                }
            }
        });


        $('#country_id').on('change', function(e){
        let country = e.target.value;
        $('#postal_code').html('');
        $('#city_id').html('');
        $('#state_id').html('');
                $.ajax({
            url: "/country-states",
            method: "POST",
            headers: {
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            data: {country: country},
            success: function(response) {
                if(response[0].country_code){
                    $('#country_id_abbrivation').val(response[0].country_code);
                }
                let states = `<option> Select states </option>`;
                response.map(state => {
                    states += `<option value="${state.iso2}">${state.name}</option>`;
                })
                $('#state_id').html(states);
            }
        })
        });

        $('#state_id').on('change', function(e){
        let country = e.target.value;
        $('#city_id').html('');
        $('#postal_code').html('');
        });
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH /Users/dev/Documents/Personal Projects/doctor-appointment-system/resources/views/templates/basic/sections/search.blade.php ENDPATH**/ ?>