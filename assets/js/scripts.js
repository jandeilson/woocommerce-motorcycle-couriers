function printData() {
    var divToPrint=document.getElementById("printTable");
   newWin= window.open();
   newWin.document.write('<html><head><meta http-equiv="Content-Type" content="text/html; charset=gb18030"><title>Reports Motorcycle Couriers</title><style type="text/css">body {margin: auto;} table, th, td {border-collapse: collapse; border: 1px solid black; text-align: center; padding: 4px;}</style></head><body>');
   newWin.document.write( divToPrint.outerHTML );
   newWin.document.write('</body></html>');
   newWin.print();
   newWin.close();
};

jQuery('button').on('click',function() {
    printData();
});

jQuery( function ( $ ) {
    $('.datepicker').datepicker({
        dateFormat : 'yy-mm-dd'
    });
});