$(document).ready(function(){

	$(".edit-dogs").click(function (event) {    
		var parent = $(this).parent();

		var data = {};
		data.dogName = $(parent).children('.nameTd').text();
		data.officialName = $(parent).children('.officialNameTd').text();
		data.color = $(parent).children('.colorTd').text();
		data.breader = $(parent).children('.breaderTd').text();
		data.weight = $(parent).children('.weightTd').text();
		data.height = $(parent).children('.heightTd').text();
		data.mental = $(parent).children('.mentalTd').text();
		data.birthdate = $(parent).children('.birthdateTd').text();
		data.dogImgPath = $(parent).children('.imageDogTd').text();
		data.genImgPath = $(parent).children('imageGenTableTd').text();
		data.description = $(parent).children('.descTd').text();

		console.log(data);


        if (!Modernizr.inputtypes.date) {
           $("#edit-birthdate").datepicker('setDate', data.birthdate);
           $("#edit-birthdate").datepicker('disable');
        } else {
        	$("#edit-birthdate").val(data.birthdate);
        }

		$("#edit-dog-name").val(data.dogName);
		$("#edit-official-name").val(data.officialName);
		$("#text-dog-name").html(data.dogName);
		$("#text-official-name").html(data.officialName);
		$("#edit-color").val(data.color);
		$("#edit-breader").val(data.breader);
		$("#edit-weight").val(data.weight);
		$("#edit-height").val(data.height);
		$("#edit-mental").val(data.mental);
		$("#edit-img-dog").val(data.dogImgPath);
		$("#edit-img-gen-table").val(data.genImgPath);
		$("#edit-description").val(data.description);
		
		$('html, body').animate({ scrollTop: $(document).height() }, 500);
		$('#update-delete-form').show(300);	
	});
	//<i class="fa fa-minus"></i>
	$('#add-dog-header').click(function(){
		$('#add-dog-form').toggle(400);
		$('#add-dog-icon').toggleClass('cursor-pointer fa-plus fa-minus');
	})
});