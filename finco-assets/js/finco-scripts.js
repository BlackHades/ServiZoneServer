/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function (e) {
    $("button.hamburger.btn-link")[0].click();
    $(".site-footer-right").html("Created and Powered by <a href='http://fincoapps.com' target='_blank' style='font-weight:bold; color: orange'>FincoApps</a>");


    var deleteFormAction;
    $('td').on('click', '.block', function (e) {
        var id = $(e.target).attr('data-expertID');
        $('#blockBtn').attr('href', "expert/block/" + id);
        $('#blockBtn').val('Yes');

        $('#blockBtn').click(function () {
            window.location.href = "expert/block/" + id;
        });

        $('#block_modal').modal('show');
    });
});
