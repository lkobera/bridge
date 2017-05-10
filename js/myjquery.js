// JavaScript Document

$(document).ready(function(){
	

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});


/*LOGIN*/
$('#button_backdoor').click(function() {
	$( "#backdoor_form" ).submit();
	
});

/*LOGOUT*/
$(document).off('click', '#button_logout').on('click', '#button_logout',function() {
	var xmlhttp;
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET","ajax/ajax_logout.php",true);
	xmlhttp.send();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload();
		}
	}
});


/*DASHBOARD*/
/*precteni release notes*/
$('#button_w_new_version').click(function(){
	var xmlhttp;
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET","/widgets/w_new_version_ajax.php",true);
	xmlhttp.send();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload();
		}
	}
});

/*REGISTRACE*/

/*vymaz zaka z registrace*/
$(document).off('click', '.registrace_delete').on('click', '.registrace_delete',function() {
	var GUID=$(this).closest('tr').attr('id');
	if (confirm('Vymazat žáka z registrace?')==1) {;
		var xmlhttp;
		xmlhttp=new XMLHttpRequest();
		xmlhttp.open("GET","ajax/ajax_registrace_delete.php?GUID="+GUID,true);
		xmlhttp.send();
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				$('#registrace_content').load('ajax/ajax_registrace_vycvik.php?inc_path=../',true);		
			}
		}
	}
});


/*Application Status management*/
/*odevzdani prihlasky - doktora - posun do hlasenky*/

$(document).off('click', '.app_status').on('click', '.app_status',function() {
	var GUID=$(this).closest('tr').attr('id');
	var xmlhttp;
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET","ajax/ajax_registrace_application.php?GUID="+GUID,true);
	xmlhttp.send();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			$('#registrace_content').load('ajax/ajax_registrace_vycvik.php?inc_path=../',true);		
		}
	}
});


/*vytvoreni hlasenky z preview, presun zaku do matriky*/

$(document).off('click', '#button_hlasenka_create').on('click', '#button_hlasenka_create',function() {
	if (confirm  ("Vytvořit hlášenku a přesunout tyto žáky z registrace do matriky?")) {
		var xmlhttp;
		xmlhttp=new XMLHttpRequest();
		xmlhttp.open("GET","ajax/ajax_hlasenka_create.php",true);
		xmlhttp.send();
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				$('#registrace_content').load('ajax/ajax_registrace_vycvik.php?inc_path=../',true);		
			}
		}	
	}
});                                                     

/*kontrola data zahajeni - >= today*/

$('.hlasenkadatum').change(function() {
	var ID=$(this).closest('tr').attr('id');
	
	var safedate=new Date();
	var hlasenkadate=new Date( $(this).val().replace( /(\d{2}).(\d{2}).(\d{4})/, "$2/$1/$3") ); /*regulerni vyraz predela datum z formatu dd.mm.yyyy na mm/dd/yyyy*/
	safedate.setHours(0,0,0,0);
	hlasenkadate.setHours(0,0,0,0);

	if (safedate <= hlasenkadate) {
		$('#button_hlasenka_ok_'+ID).removeClass('btn-default disabled');
		$('#button_hlasenka_ok_'+ID).addClass('btn-success');
	}
	else {
		$('#button_hlasenka_ok_'+ID).removeClass('btn-success');
		$('#button_hlasenka_ok_'+ID).addClass('btn-default disabled');
	}
});



/*uzavreni hlasenky*/
$('.button_hlasenka_ok').click(function() {
	var folderID=$(this).closest('tr').attr('id');
	var datum=$('#hlasenkadatum_'+folderID).val();
	var autor=$('#autor').val();
	var xmlhttp;
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET","ajax/ajax_hlasenka_lock.php?folderID="+folderID+"&datum="+datum+"&autor="+autor,true);
	xmlhttp.send();
	xmlhttp.onreadystatechange = function() {
  		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload();	
	  	}
	}
});

/*export hlasenky do XML */
$('.button_xml_hlasenka').click(function() {
	var hlasenka=$(this).val();
	var xmlhttp;
	xmlhttp=new XMLHttpRequest();
	window.location = 'ajax/ajax_create_xml_file.php?hlasenka='+hlasenka;
	xmlhttp.send();
});

/*MATRIKA*/

/*export evidencni knihy do CSV */
$(document).off('click', '.testb').on('click', '.testb',function() {
	var xmlhttp;
	xmlhttp=new XMLHttpRequest();
	window.location = 'ajax/ajax_create_xml_file.php?evidencni_kniha';
	xmlhttp.send();

});


/*zmen filtru podle ucitele*/
$(document).off('change', '#matrika_select_ucitel').on('change', '#matrika_select_ucitel',function() {
	var ucitel=$(this).val();
	window.location.replace("/?menu=matrika&ucitel="+ucitel);
});	

/*zmena ucitele v matrice*/
$(document).off('change', '.matrika_ucitel').on('change', '.matrika_ucitel',function() {
	var GUID=$(this).closest('table').closest('tr').attr('id');
	var column=$(this).attr('name');
	var value=$(this).val();
	if (confirm ("Potvrdit změnu učitele výcviku?")) {
		var xmlhttp;
		xmlhttp=new XMLHttpRequest();
		xmlhttp.open("GET","ajax/ajax_matrika_instruktor_update.php?GUID="+GUID+"&column="+column+"&value="+value,true);
		xmlhttp.send();
		xmlhttp.onreadystatechange = function() {
	  		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				location.reload();
			}
		}
	}
	else location.reload();
});


/*editace poznamky*/
$(document).off('click', '.btn-poznamka-edit').on('click', '.btn-poznamka-edit',function() {
	var GUID=$(this).val();
	
	$('#button_poznamka_save').val(GUID);
	document.getElementById("poznamka-text").innerHTML = poznamka[GUID];
	document.getElementById("poznamka_ext-text").innerHTML = poznamka_ext[GUID];
});

/*ulozeni poznamky*/
$(document).off('click', '#button_poznamka_save').on('click', '#button_poznamka_save',function() {
	var GUID=$(this).val();
	var text=$('#poznamka-text').val();
	var text_ext=$('#poznamka_ext-text').val();
	
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET","ajax/ajax_matrika_poznamka_update.php?GUID="+GUID+"&text="+text+'&text_ext='+text_ext,true);
	xmlhttp.send();
	xmlhttp.onreadystatechange = function() {
 		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload();
		}
	}
});

/*ZKOUSKY*/

/*vlozeni noveho terminu zkousky*/
$('.zkousky_insert').click(function() {
	var datum = $(this).attr('value');
	var xmlhttp;
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET","ajax/ajax_zkousky_insert.php?datum="+datum,true);
	xmlhttp.send();

	xmlhttp.onreadystatechange = function() {
  		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			$("#td_"+datum).load("ajax/ajax_zkousky_table.php?datum="+datum+"&inc_path=../",true);	
			location.reload();	
	  	}
	}
	
});




/*zamceni terminu zkousky*/
$(document).off('click', '.zkousky_lock').on('click', '.zkousky_lock',function() {
	$(this).toggleClass("btn-info");
	$(this).toggleClass("btn-default");
	$(this).closest('tr').toggleClass("active");
	var ZKid = $(this).attr('value');
	var action = $(this).attr('name');
	var xmlhttp;
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open('GET','ajax/ajax_zkousky_lock.php?ZKid='+ZKid,true);
	xmlhttp.send();
	xmlhttp.onreadystatechange = function() {
  		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			$('#edit'+ZKid).toggleClass("disabled");
	  	}
	}

});



/*vlozeni noveho zaka do zkousky*/
$('.zkousky_zaci_insert').click(function() {
	var ZKid=$(this).closest('div').attr('id');
	var GUID=$("select[name='"+ZKid+"']").val();
	var xmlhttp;
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET","ajax/ajax_zkousky_zaci_insert.php?GUID="+GUID+"&ZKid="+ZKid,true);
	xmlhttp.send();
	xmlhttp.onreadystatechange = function() {
  		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			$("#"+ZKid).load("ajax/ajax_zkousky_zaci_table.php?ZKid="+ZKid+"&inc_path=../",true);		
	  	}
	}
});

/*vymaz zaka ze zkousky - omluvena absence*/
$('.zkousky_zaci_delete').click(function() {
	var ZKid=$(this).closest('div').attr('id');
	var ID=$(this).closest('tr').attr('id');
	var xmlhttp;
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET","ajax/ajax_zkousky_zaci_delete.php?ID="+ID,true);
	xmlhttp.send();
	xmlhttp.onreadystatechange = function() {
  		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			$("#"+ZKid).load("ajax/ajax_zkousky_zaci_table.php?ZKid="+ZKid+"&inc_path=../",true);		
	  	}
	}
});

/*změna obsahu zkoušky v přihláškách ke zkoušce*/
$(document).off('click', '.ZKinput').on('click', '.ZKinput',function() { /*reseni bugu, kdy jquery odpaloval tuhle fci opakovane az 60x...*/
	var ZKid=$(this).closest('div').attr('id');
	var ID=$(this).closest('tr').attr('id');
	var subject=$(this).attr('id');	
	var xmlhttp;
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET","ajax/ajax_zkousky_zaci_subject.php?ID="+ID+"&subject="+subject,true);
	xmlhttp.send();
	xmlhttp.onreadystatechange = function() {
  		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			$("#"+ZKid).load("ajax/ajax_zkousky_zaci_table.php?ZKid="+ZKid+"&inc_path=../",true);		
	  	}
	}
	
});


/*VYSLEDKY ZKOUSEK*/

/*vymaz zaka z vysledku zkousky - omluvena absence*/
$('.zkousky_vysledky_delete').click(function() {
	var ZKid=$(this).closest('div').attr('id');
	var ID=$(this).closest('tr').attr('id');
	if (confirm('Odstanit žáka ze zkušebního seznamu?')) {
		var xmlhttp;
		xmlhttp=new XMLHttpRequest();
		xmlhttp.open("GET","ajax/ajax_zkousky_zaci_delete.php?ID="+ID,true);
		xmlhttp.send();
		xmlhttp.onreadystatechange = function() {
	  		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				$("#"+ZKid).load("ajax/ajax_zkousky_vysledky_table.php?ZKid="+ZKid+"&inc_path=../",true);		
		  	}
		}
	}
});


$(document).off('click', '.zkousky_vysledky_refresh').on('click', '.zkousky_vysledky_refresh',function() { /*reseni bugu, kdy jquery odpaloval tuhle fci opakovane az 60x...*/
	var dateshift=$(this).val();
	$(".accordion").accordion("destroy");
	$(".accordion").load("ajax/ajax_zkousky_vysledky.php?dateshift="+dateshift+"&inc_path=../").accordion();
});


/*vysledky zkousky - switch statusu*/
$(document).off('click', '.zk_switch').on('click', '.zk_switch',function() {
	/*zmeny checkbox buttonu*/
	var ID=$(this).closest('tr').attr('id');
	
	if ($(this).val()=='') {
		$(this).children().removeClass('glyphicon-unchecked');
		$(this).children().addClass('glyphicon-thumbs-down');
		$(this).val(0);
		$(this).removeClass();
		$(this).addClass('zk_switch btn btn-danger');
	}	
	else if ($(this).val()==0) {
		$(this).children().removeClass('glyphicon-thumbs-down');
		$(this).children().addClass('glyphicon-thumbs-up');
		$(this).val(1);
		$(this).removeClass();
		$(this).addClass('zk_switch btn btn-success');
	}
	else if ($(this).val()==1) {
		$(this).children().removeClass('glyphicon-thumbs-up');
		$(this).children().addClass('glyphicon-thumbs-down');
		$(this).val(0);
		$(this).removeClass();
		$(this).addClass('zk_switch btn btn-danger');
	}
	
	/*zmena save buttonu*/
	$('#zk_save_'+ID).removeClass('btn-default disabled');
	$('#zk_save_'+ID).addClass('btn-success');
	$('#zk_save_'+ID).children().removeClass();
	$('#zk_save_'+ID).children().addClass('glyphicon glyphicon-save');
});



/*vysledky zkousky - save*/
$(document).off('click', '.zk_save').on('click', '.zk_save',function() {
	var ID= $(this).closest('tr').attr('id');	
	var PPV=$('#PPV_'+ID).val();
	var PJ_A=$('#PJ_A_'+ID).val();
	var PJ_B=$('#PJ_B_'+ID).val();
	var ucitel_A=$('#ucitel_A_'+ID).val();
	var ucitel_B=$('#ucitel_B_'+ID).val();
	var repro=$('#repro_'+ID).val();
	
	if ($(this).hasClass('btn-success')) {
		$(this).removeClass('btn-success');
		$(this).addClass('btn-default disabled');
		$(this).children().removeClass();
		$(this).children().addClass('glyphicon glyphicon-saved');
		if(PPV !='') $('#PPV_'+ID).addClass('disabled');
		if(PJ_A !='') $('#PJ_A_'+ID).addClass('disabled');
		if(PJ_B !='') $('#PJ_B_'+ID).addClass('disabled');

		var xmlhttp;
		xmlhttp=new XMLHttpRequest();
		xmlhttp.open('GET','ajax/ajax_zkousky_vysledky_update.php?ID='+ID+'&resultPPV='+PPV+'&resultPJ_A='+PJ_A+'&resultPJ_B='+PJ_B+'&ucitel_A='+ucitel_A+'&ucitel_B='+ucitel_B+'&repro='+repro,true);
		xmlhttp.send();
	}
	
});

/*export seznamu do XML */
$('.button_xml_seznam').click(function() {
	var seznam=$(this).val();
	var xmlhttp;
	xmlhttp=new XMLHttpRequest();
	window.location = 'ajax/ajax_create_xml_file.php?seznam='+seznam;
	xmlhttp.send();

});




/*VALIDATOR PLATBY KURZOVNE*/

$('#input_kurzovne').on("input", function() {
	var madati=parseInt($('#madati').val());
	var platba=parseInt($('#input_kurzovne').val());
	if (platba>0) {
		if ((platba<1) ||(madati < platba)) {
			$('#button_pay_kurzovne').removeClass('btn-success');
			$('#button_pay_kurzovne').addClass('btn-default disabled');
			$('#button_pay_kurzovne').attr('data-toggle','');
		}
		else {
			$('#button_pay_kurzovne').removeClass('btn-default disabled');
			$('#button_pay_kurzovne').addClass('btn-success');
			$('#button_pay_kurzovne').attr('data-toggle','modal');
		}
	}
	else {
		$('#button_pay_kurzovne').removeClass('btn-success');
		$('#button_pay_kurzovne').addClass('btn-default disabled');
		$('#button_pay_kurzovne').attr('data-toggle','');
	}
	
	$('#invoice_price').text(platba);
	
});


/*NULOVANI FORMULARE KONDIC PRI ZMENE polozky + blokace poctu pokud neni vybrana polozka + blokace submitu*/
$('#polozka_kondice').change (function() {
	var ID=$(this).val();
	$('#pocet_kondice').val('0');
	$('#cena_total').val('0');
	
	if (ID==0) {$('#pocet_kondice').attr('disabled','disabled');}
	else {$('#pocet_kondice').removeAttr('disabled');}
	
	$('#button_pay_kondice').removeClass('btn-success');
	$('#button_pay_kondice').addClass('btn-default disabled');
	$('#button_pay_kondice').attr('data-toggle','');
	
});

/*KALKULACE CENY - KONDICE a SLUZBY + odblokovani submitu + prenos info do modalu*/
$('#pocet_kondice').on("input", function() {
	var ID=$('#polozka_kondice').val();
	var cena=$('#cena_kondice_'+ID).val();
	var pocet=$(this).val();
	var total=cena*pocet;
	var ucel=$('#cenik_polozka_'+ID).val()+' ('+pocet+')';
	
	$('#cena_total').val(total);
	$('#invoice_price').text(total);
	$('#invoice_ucel').text(ucel);
	
	$('#input_kurzovne').val(total);
	$('#invoice_ID').val(ID);
	$('#invoice_pocet').val(pocet);
	
	
	
	
	if (pocet<1) {
		$('#button_pay_kondice').removeClass('btn-success');
		$('#button_pay_kondice').addClass('btn-default disabled');
		$('#button_pay_kondice').attr('data-toggle','');
	}
	else {
		$('#button_pay_kondice').removeClass('btn-default disabled');
		$('#button_pay_kondice').addClass('btn-success');
		$('#button_pay_kondice').attr('data-toggle','modal');
	}
});

/*ZAUCTOVANI PLATBY*/
$('#button_zauctovat').click(function() {
	var GUID=$('#invoice_GUID').val();
	var autor=$('#invoice_autor').val();
	var datum=$('#invoice_datum').val();
	var platba=$('#input_kurzovne').val();
	var zdroj=$('#invoice_zdroj').val();
	var cenikID=$('#invoice_ID').val();
	var pocet=$('#invoice_pocet').val();
	if (confirm('Zaúčtovat platbu a zavřít okno?')) {
	
		var xmlhttp;
		xmlhttp=new XMLHttpRequest();
		xmlhttp.open('GET','ajax/ajax_platby_insert.php?GUID='+GUID+'&autor='+autor+'&datum='+datum+'&platba='+platba+'&cenikID='+cenikID+'&pocet='+pocet,true);
		xmlhttp.send();
		xmlhttp.onreadystatechange = function() {
	  		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				/*window.location('/?menu=platby_detail&zdroj='+zdroj+'&GUID='+GUID,true);*/
				location.reload(); 
		  	}
		}
	}
});

/*TRIDNICE PRAXE*/

/*zmen filtru podle ucitele*/
$(document).off('change', '#praxe_select_ucitel').on('change', '#praxe_select_ucitel',function() {
	var ucitel=$(this).val();
	window.location.replace("/?menu=praxe&ucitel="+ucitel);

	
});

/*vlozeni datumu jizdy do tridnice*/
$(document).off('click', '.button_praxe_insert').on('click', '.button_praxe_insert',function() {
	var GUID=$(this).val();
	var skupina=$(this).closest('table').attr('id');
	var datum=$('#datum_'+skupina).val();
	var ucitel=$('#ucitelID_'+skupina).val();
	
	if (datum=='') { 
		alert('Chyba - prázdné datum!');
	}
	else {
		if (confirm('Zapsat hodinu výcviku?')==true) {
			var xmlhttp;
			xmlhttp=new XMLHttpRequest();
			xmlhttp.open("GET","ajax/ajax_praxe_insert.php?GUID="+GUID+"&skupina="+skupina+"&datum="+datum+'&ucitel='+ucitel,true);
			xmlhttp.send();
			
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					$("#output").load("ajax/ajax_praxe_table.php?GUID="+GUID+"&inc_path=../",false);
						
				}
			}
		}
	}
});

/*vymaz datumu jizdy do tridnice*/
$(document).off('click', '.button_praxe_delete').on('click', '.button_praxe_delete',function() {
	var GUID=$(this).val();
	var ID=$(this).attr('data-id');

	if (confirm('Vymazat hodinu výcviku?')==true) {
		var xmlhttp;
		xmlhttp=new XMLHttpRequest();
		xmlhttp.open("GET","ajax/ajax_praxe_delete.php?ID="+ID,true);
		xmlhttp.send();
		
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				$("#output").load("ajax/ajax_praxe_table.php?GUID="+GUID+"&inc_path=../",true);		
			}
		}
	}

});

/*kontrola datumu ukonceni*/
$('.closedate').change(function() {
	var safedate=new Date($('#safedate').val());
	closedate=new Date( $(this).val().replace( /(\d{2}).(\d{2}).(\d{4})/, "$2/$1/$3") ); /*regulerni vyraz predela datum z formatu dd.mm.yyyy na mm/dd/yyyy*/
	safedate.setHours(0,0,0,0);
	closedate.setHours(0,0,0,0);

	if (safedate <= closedate) {
		$('.button_closedate').removeClass('btn-default disabled');
		$('.button_closedate').addClass('btn-success');
	}
	else {
		$('.button_closedate').removeClass('btn-success');
		$('.button_closedate').addClass('btn-default disabled');
	}
});


/*ulozeni data ukonceni*/
$('.button_closedate').click(function() {
	var GUID=$(this).closest('table').attr('id');
	var varianta=$(this).val();
	closedate=$('#closedate_'+varianta).val();
	
	if ($(this).hasClass('btn-success')) {
		var xmlhttp;
		xmlhttp=new XMLHttpRequest();
		xmlhttp.open('GET','ajax/ajax_praxe_closedate.php?GUID='+GUID+'&closedate='+closedate,true);
		xmlhttp.send();
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				location.reload(); 
			}
		}
	}
	else location.reload();
	
	
});

/*komba details*/
$(document).off('change', '.pj_detail').on('change', '.pj_detail',function() {
	if ($(this).val()!="") {
		$(this).closest('div').find('button').removeClass('btn-default').removeAttr('disabled').addClass('btn-success');
	}
	else $(this).closest('div').find('button').removeClass('btn-success').attr('disabled','').addClass('btn-default').addClass('btn-default');
});


/*save kombo detail*/
$(document).off('click', '.btn_pj_detail').on('click', '.btn_pj_detail',function() {	
	$(this).addClass('hidden');
	$(this).closest('div').find('.pj_detail').attr('disabled','');
	$.post("ajax/ajax_praxe_detail_insert.php",	
    {
		ID: $(this).attr('data-id'),
        column: $(this).closest('div').find('.pj_detail').attr('name'),
        value: $(this).closest('div').find('.pj_detail').val()
    });
});



/*save kombo cas*/

$(document).off('click', '.btn_pj_cas').on('click', '.btn_pj_cas',function() {	
	$(this).addClass('hidden');
	$(this).closest('div').find('.pj_cas_od').attr('disabled','');
	$.post("ajax/ajax_praxe_detail_insert.php",	
    {
		ID: $(this).attr('data-id'),
        column: 'cas_od',
        value: $(this).closest('div').find('.pj_cas_od').val()
    });
	$.post("ajax/ajax_praxe_detail_insert.php",	
    {
		ID: $(this).attr('data-id'),
        column: 'cas_do',
        value: $(this).closest('div').find('.pj_cas_do').val()
    });	
});



/*ADMIN TOOLS*/
/*new user*/
$(document).off('click', '.button_new_user').on('click', '.button_new_user',function() {
	var jmeno=$('#new_user_name').val();
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open('GET','ajax/ajax_registr_user_new.php?jmeno='+jmeno, true);
	xmlhttp.send();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(); 
		}
	}
});

/*new vehicle*/
$(document).off('click', '.button_new_vehicle').on('click', '.button_new_vehicle',function() {
	var rz=$('#new_vehicle_rz').val();
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open('GET','ajax/ajax_registr_vehicle_new.php?rz='+rz, true);
	xmlhttp.send();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(); 
		}
	}
});


/*update prava*/
$(document).off('click', '.switch_prava').on('click', '.switch_prava',function() {
	var column=$(this).attr('id');
	var UZid=$(this).closest('tr').attr('id');
	var xmlhttp;
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open('GET','ajax/ajax_registr_user_prava.php?UZid='+UZid+'&column='+column, true);
	xmlhttp.send();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(); 
		}
	}
});

/*insert pausal*/
$('.button_pausal').click(function() {
	var UZid=$('#UZid').val();
	var skupina=$(this).val();
	var pausal=$('#pausal_'+skupina).val();
	var datum=$('#datum_'+skupina).val();
	
	if (confirm ('Uložit '+skupina+' '+pausal+' od '+datum)) {
		var xmlhttp;
		xmlhttp=new XMLHttpRequest();
		xmlhttp.open('GET','ajax/ajax_registr_user_pausal_update.php?UZid='+UZid+'&datum='+datum+'&pausal='+pausal+'&skupina='+skupina, true);
		xmlhttp.send();
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				location.reload(); 
			}
		}
	}
	else location.reload();
	
});


/*VYKAZ PRACE*/

/*zmen filtru podle ucitele*/
$(document).off('change', '#vykaz_select_ucitel').on('change', '#vykaz_select_ucitel',function() {
	var ucitel=$(this).val();
	window.location.replace("/?menu=vykaz&ucitel="+ucitel);
});	

/*detail mesice*/
$(document).off('click', '.button_vykaz_mesic_open').on('click', '.button_vykaz_mesic_open',function() {
	var id=$(this).closest('tr').attr('id');
	$('#ucitel_vykaz').load('ajax/ajax_ucitel_vykaz_table.php?inc_path=../'+'&open='+id,true);	

	
});

/*zalozeni noveho vyuctovani*/
$(document).off('click', '.button_ucitel_new_invoice').on('click', '.button_ucitel_new_invoice',function() {
	var invoiceID=$(this).val();
	var xmlhttp;
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open('GET','ajax/ajax_ucitel_invoice_new.php?invoiceID='+invoiceID, true);
	xmlhttp.send();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			window.location.replace("/?menu=ucitel_invoice_edit&invoiceID="+invoiceID);
		}
	}
	
});


/*nova custom polozka do invoice*/

$(document).off('click', '#button_invoice_custom_add').on('click', '#button_invoice_custom_add',function() {
	var invoiceUID=$(this).val();
	var popis=$('#ucitel_vykon').val();
	var cena_jednotka=$('#ucitel_vykon_cena_jednotka').val();
	var pocet=$('#ucitel_vykon_pocet').val();
	var xmlhttp;
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open('GET','ajax/ajax_ucitel_invoice_add.php?invoiceUID='+invoiceUID+'&popis='+popis+'&cena_jednotka='+cena_jednotka+'&pocet='+pocet, true);
	xmlhttp.send();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(); 
		}
	}
});

/*vymazat custom polozku z invoice*/
$(document).off('click', '.button_invoice_custom_delete').on('click', '.button_invoice_custom_delete',function() {
	var ID=$(this).val();
	var xmlhttp;
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open('GET','ajax/ajax_ucitel_invoice_delete.php?ID='+ID, true);
	xmlhttp.send();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(); 
		}
	}
});

/*add button kontrola a aktivace*/
$(document).off('change', '#ucitel_vykon').on('change', '#ucitel_vykon',function() {
	customInvoiceTest();	
});
$(document).off('change', '#ucitel_vykon_cena_jednotka').on('change', '#ucitel_vykon_cena_jednotka',function() {
	customInvoiceTest();	
});
$(document).off('change', '#ucitel_vykon_pocet').on('change', '#ucitel_vykon_pocet',function() {
	customInvoiceTest();	
});

function customInvoiceTest() {
	if (($('#ucitel_vykon_cena_jednotka').val()>0)&&($('#ucitel_vykon_pocet').val()>0))  {
		$('#button_invoice_custom_add').removeAttr('disabled');
		$('#button_invoice_custom_add').removeClass('btn-default');
		$('#button_invoice_custom_add').addClass('btn-success');
	}
	else {
		$('#button_invoice_custom_add').addAttr('disabled');
		$('#button_invoice_custom_add').addClass('btn-default');
		$('#button_invoice_custom_add').removeClass('btn-success');
	}
}

/*odpocet odvodu od vyplaty*/
$(document).off('input', '#odvody').on('input', '#odvody',function() {
	var odvody=$('#odvody').val();
	var hrubamzda= $('#hrubamzda').html();
	$('#mzda').html(hrubamzda-odvody);
});

/*lock proces na hotovem invoice*/
$(document).off('click', '#ucitel_invoice_lock').on('click', '#ucitel_invoice_lock',function() {
	var invoiceUID=$(this).val();
	var vycvik_A=$('#vycvik_A').html();
	var zk_A=$('#zk_A').html();
	var rep_A=$('#rep_A').html();
	var vycvik_B=$('#vycvik_B').html();
	var zk_B=$('#zk_B').html();
	var rep_B=$('#rep_B').html();
	
	var pausal_A=$('.pausal_A').html();
	var pausal_B=$('.pausal_B').html();
	
	var odvody=$('#odvody').val();
	
	if (confirm('Uzavřít vyúčtování? Tento krok nelze vzít zpět.')) {
		var xmlhttp;
		xmlhttp=new XMLHttpRequest();
		xmlhttp.open('GET','ajax/ajax_ucitel_invoice_lock.php?invoiceUID='+invoiceUID+'&odvody='+odvody+'&vycvik_A='+vycvik_A+'&zk_A='+zk_A+'&rep_A='+rep_A+'&pausal_A='+pausal_A+'&vycvik_B='+vycvik_B+'&zk_B='+zk_B+'&rep_B='+rep_B+'&pausal_B='+pausal_B, true)
		xmlhttp.send();
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				location.reload(); 
			}
		}
	}
	
});




/*MOJE POKLADNA*/
/*zmen filtru podle ucitele*/
$(document).off('change', '#pokladna_select_ucitel').on('change', '#pokladna_select_ucitel',function() {
	var ucitel=$(this).val();
	window.location.replace("/?menu=ucitel_pokladna&ucitel="+ucitel);
});	

/*kontrola datumu a castky vydeje z pokladny*/
$('#ucitel_vydej_castka').on("input", function() {
	var platba=parseInt($('#ucitel_vydej_castka').val());
	if (platba>0) {
		if (platba<1)  {
			$('#button_pokladna_vydej').removeClass('btn-success');
			$('#button_pokladna_vydej').addClass('btn-default disabled');			
		}
		else {
			$('#button_pokladna_vydej').removeClass('btn-default disabled');
			$('#button_pokladna_vydej').addClass('btn-success');
		}
	}
	else {
		$('#button_pokladna_vydej').removeClass('btn-success');
		$('#button_pokladna_vydej').addClass('btn-default disabled');
	}

});

/*provozni zaloha - ukaz pole s vyberem komu*/

$(document).off('change', '#ucitel_vydej_ucet').on('change', '#ucitel_vydej_ucet',function() {
	if ($('#ucitel_vydej_ucet').val()>999000){
		$('#poznamka').addClass('hide');
		$('#zaloha_komu').removeClass('hide');
	}
	else {
		$('#poznamka').removeClass('hide');
		$('#zaloha_komu').addClass('hide');
	}
});


/*vydej z pokladny*/
$(document).off('click', '#button_pokladna_vydej').on('click', '#button_pokladna_vydej',function() {
	var datum=$('#ucitel_vydej_datum').val();
	var ucet=$('#ucitel_vydej_ucet').val();
	var popis=$('#ucitel_vydej_popis').val();
	var castka=$('#ucitel_vydej_castka').val();
	var autor=$('#autor').val();
	var uzivatel_ucet=$('#uzivatel_ucet').val();
	
	if ($(this).hasClass('btn-success')) {
		xmlhttp=new XMLHttpRequest();
		if (ucet>999000) { /*interni operace - vydej zaloh a vyuctovani*/
			var komu=$('#ucitel_zaloha_komu').val();
			if (ucet==999999) xmlhttp.open('GET','ajax/ajax_ucitel_pokladna_zaloha.php?datum='+datum+'&ucet='+ucet+'&komu='+komu+'&castka='+castka+'&autor='+autor+'&uzivatel_ucet='+uzivatel_ucet, true); /*zaloha*/
			if (ucet==999990) xmlhttp.open('GET','ajax/ajax_ucitel_pokladna_vydej.php?datum='+datum+'&ucet='+ucet+'&popis='+komu+'&castka='+castka+'&autor='+autor+'&uzivatel_ucet='+uzivatel_ucet, true); /*mzda*/
		}
		else {	/*bezne vydaje podle ucetni osnovy*/
			xmlhttp.open('GET','ajax/ajax_ucitel_pokladna_vydej.php?datum='+datum+'&ucet='+ucet+'&popis='+popis+'&castka='+castka+'&autor='+autor+'&uzivatel_ucet='+uzivatel_ucet, true);
		}		
		xmlhttp.send();
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				location.reload(); 
			}
		}
	}
});


});