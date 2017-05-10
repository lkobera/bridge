<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
</head>
<body>

<select></select>

<script>
    $.getJSON('branch.json', function(json) {
		$('select').append('<option>select</option>');
        $.each(json, function() {
            $('select').append('<option>' + this.loc + '</option>');
        });
    });
	
	
	
	
/*xmlhttp.open("GET", url, true);
xmlhttp.send();

xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        var myArr = JSON.parse(xmlhttp.responseText);
        myFunction(myArr);
    }
};*/


function myFunction(arr) {
    var out = "";
    var i;
    for(i = 0; i < arr.length; i++) {
        out += arr[i].loc + '<br>';
    }
    document.getElementById("id01").innerHTML = out;
}
</script>

</body>
</html>
