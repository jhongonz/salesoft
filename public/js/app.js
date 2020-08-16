const STATUS_OK = 2;
const STATUS_FAIL = 1;
const DB_TRUE = 1;
const DB_FALSE = 0;
const BASE_WEB_ROOT = 'http://gnomo.local';
const DOM_TABLE = "<'row'<'col-sm-3'B><'col-sm-6 text-left'l><'col-sm-3'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";

function valideKeyNumber(e, decimal=false)
{
    var code = (e.which) ? e.which : e.keyCode;
    if (code == 8 || code == 9 || code == 13){ return true; }
        
    var pattern = (!decimal) ? /^[0-9]$/ : /^[0-9.]$/;
    var finishKey = String.fromCharCode(code);
    
    return pattern.test(finishKey);
}