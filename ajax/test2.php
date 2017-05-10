<!DOCTYPE html>
<html>
<body>

<div id="id01"></div>

<script>
var xmlhttp = new XMLHttpRequest();
var url = "branch.json";

xmlhttp.open("GET", url, true);
xmlhttp.send();

xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        var myArr = JSON.parse(xmlhttp.responseText);
        myFunction(myArr);
    }
};


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
