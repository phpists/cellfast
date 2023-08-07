jQuery(document).on('click', '.catalog .filter_goselect', function(e){
    e.preventDefault();
    var filterurl =  jQuery(this).attr('href');

    window.location.href = filterurl;
});