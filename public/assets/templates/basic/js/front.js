function resetModal() {
    $('#petCreatedForm')[0].reset();
    $('#div1').show();
    $('#div2').hide();
    $('.cover_spin_petCreated').hide();
  }
$('#petCreatedForm').on('submit', function(e){
  e.preventDefault();
  const $imagesInput = $('#images');
  const $videoInput = $('#video');

  if ($('#name').val() == '' && $imagesInput[0].files.length === 0 && $('#age').val() == '' && $videoInput[0].files.length === 0 && $('#short_description').val() == '' ) {
         $('#PetCreateButton').attr('disabled','disabled');
      }else{
          $('.cover_spin_petCreated').show();
          $.ajax({
          url:'/myPets',
          method:'POST',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
          },
          data:new FormData(this),
          processData:false,
          dataType:'json',
          contentType:false
          }).done(function(response){
            $("#loadermsg").show();
            $("#loadermsg").html(response.message);
            $('#petCreatedForm')[0].reset();
            if (response.page_type == 'mypets') {
              $("#loadpet").hide().load(" #loadpet"+"> *").fadeIn(0);
            }
            if(response.page_type == 'booking'){
              $(".changeButtonAppointment").hide().load(" .changeButtonAppointment"+"> *").fadeIn(0);
            }
            $("#div1").hide();
            $('.cover_spin_petCreated').hide();
            $("#div2").show();
            // $('#exampleModal').modal('hide');
            $("#loadermsg").fadeOut(3000);
            return response;

          });
      }
      $('#PostCreateButton').attr('disabled','disabled');
  });
  $('#preRecordForm').on('submit', function(e){
    e.preventDefault();
            $('.cover_spin_petCreated').show();
            $.ajax({
            url:'/myPets',
            method:'POST',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data:new FormData(this),
            processData:false,
            dataType:'json',
            contentType:false,
            }).done(function(response){
                $('#exampleModal').modal('hide');
                if(response.success){
                    notify('success',response.message);
                }else{
                    notify('error',response.message);
                    return false;
                }
            });
    });
$('#exampleModal').on('hidden.bs.modal', function () {
    resetModal()
  });
function deleteSinglePetAttachment(id, type){
  $.ajax({
    method:'GET',
    url:'/deleteSinglePetAttachment/'+id+'/'+type,
    data: {id:id, type:type},
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    }).done(function(response){
      console.log(response);
      if(response.status == 'image'){
        $(".updateDivAfterImageDelete").hide().load(" .updateDivAfterImageDelete"+"> *").fadeIn(0);
      }
      if(response.status == 'video'){
        $(".updateDivAfterVideoDelete").hide().load(" .updateDivAfterVideoDelete"+"> *").fadeIn(0);
      }
      if(response.status == 'previous_record'){
        $(".updateDivAfterPreviousRecordDelete").hide().load(" .updateDivAfterPreviousRecordDelete"+"> *").fadeIn(0);
      }
    });
}
function validateAge(input) {
    input.value = input.value.replace(/[^\d.]/g, '');
    if (input.value.length > 4) {
        input.value = input.value.slice(0, 4);
    }
}
function formatPhoneNumber(input) {
    var phoneNumberCheck = document.getElementById("phoneNumber");
    var checkregex = /[^a-zA-Z0-9]/g;
    var phoneNumber = input.replace(checkregex, "");
    var regex = /^([a-zA-Z0-9]{3})([a-zA-Z0-9]{3})([a-zA-Z0-9]{4})$/;
    if (regex.test(input)) {
        phoneNumberCheck.setCustomValidity("");
        return input.replace(regex, '($1) $2-$3');
    } else {
        if(phoneNumber.length < 10 || phoneNumber.length > 11){
            event.preventDefault();
            phoneNumberCheck.setCustomValidity("Phone number must be 10 digits");
            return input;
        }else{
            phoneNumberCheck.setCustomValidity("");
            return input;
        }
    }
}

const stars = document.querySelectorAll('.star');

    stars.forEach(star => {
        star.addEventListener('click', () => {
            const rating = star.getAttribute('data-rating');
            document.getElementById('selected-rating').value = rating;

            // Remove the "checked" class from all stars
            stars.forEach(s => s.classList.remove('checked'));

            // Add the "checked" class to the clicked star
            star.classList.add('checked');
        });
    });
