$(document).ready(function(){

	$("tbody tr").click(function (event) {    
		var parent = $(event.target).parent();

		var data = {};
		data.dogName = $(parent).children('.nameTd').text();
		data.officialName = $(parent).children('.officialNameTd').text();
		data.color = $(parent).children('.colorTd').text();
		data.breader = $(parent).children('.breaderId').text();
		data.weight = $(parent).children('.weightTd').text();
		data.height = $(parent).children('.heightTd').text();
		data.mental = $(parent).children('mentalTd').text();
		data.birthdate = $(parent).children('.birthdateTd').text();
		data.dogImgPath = $(parent).children('.imageDogTd').text();
		data.genImgPath = $(parent).children('imageGenTableTd').text();
		data.description = $(parent).children('.descTd').text();

		$("#edit-dog-name").val(data.dogName);
		$("#edit-official-name").val(data.officialName);
		$("#edit-color").val(data.color);
		$("#edit-breader").val(data.breader);
		$("#edit-weight").val(data.weight);
		$("#edit-height").val(data.height);
		$("#edit-mental").val(data.mental);
		$("#edit-birthdate").val(data.birthdate);
		$("#edit-img-dog").val(data.dogImgPath);
		$("#edit-img-gen-table").val(data.genImgPath);
		$("#edit-description").val(data.description);
		
		$('html, body').animate({ scrollTop: $(document).height() }, 500);
		$('form').show(300);	
	});
});