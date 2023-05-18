var button = document.getElementById("pdfButton");
var makepdf = document.getElementById("makepdf");

button.addEventListener("click", function () {
    var mywindow = window.open("", "PRINT",
            "height=800,width=800");

    mywindow.document.write(makepdf.innerHTML);

    mywindow.document.close();
    mywindow.focus();

    mywindow.print();
    mywindow.close();

    return true;
});