// Custom scripts
$(document).ready(function()
{
    // MetsiMenu
    $('#side-menu').metisMenu();

    // Collapse ibox function
    $('.collapse-link').click(function()
    {
        var ibox = $(this).closest('div.ibox');
        var button = $(this).find('i');
        var content = ibox.find('div.ibox-content');
        content.slideToggle(200);
        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        ibox.toggleClass('').toggleClass('border-bottom');
        setTimeout(function()
        {
            ibox.resize();
            ibox.find('[id^=map-]').resize();
        }, 50);
    });

    // Close ibox function
    $('.close-link').click(function()
    {
        var content = $(this).closest('div.ibox');
        content.remove();
    });

    // Small todo handler
    $('.check-link').click(function()
    {
        var button = $(this).find('i');
        var label = $(this).next('span');
        button.toggleClass('fa-check-square').toggleClass('fa-square-o');
        label.toggleClass('todo-completed');
        return false;
    });

    // minimalize menu
    $('.navbar-minimalize').click(function()
    {
        $("body").toggleClass("mini-navbar");
        SmoothlyMenu();
    });

    // tooltips
    $('.tooltip-demo').tooltip(
    {
        selector: "[data-toggle=tooltip]",
        container: "body"
    });

    // Move modal to body
    // Fix Bootstrap backdrop issu with animation.css
    $('.modal').appendTo("body")

    // Full height of sidebar
    function fix_height()
    {
        var heightWithoutNavbar = $("body > #wrapper").height() - 61;
        $(".sidebard-panel").css("min-height", heightWithoutNavbar + "px");
    }
    fix_height();

    $(window).bind("load resize click scroll", function()
    {
        if(!$("body").hasClass('body-small'))
            fix_height();
    })

    $("[data-toggle=popover]").popover();
});

// Minimalize menu when screen is less than 768px
$(function()
{
    $(window).bind("load resize", function()
    {
        if ($(this).width() < 769)
            $('body').addClass('body-small')
        else
            $('body').removeClass('body-small')
    })
})

function SmoothlyMenu()
{
    if (!$('body').hasClass('mini-navbar') || $('body').hasClass('body-small'))
    {
        $('#side-menu').hide();
        setTimeout(function() {$('#side-menu').fadeIn(500);}, 100);
    }
    else if ($('body').hasClass('fixed-sidebar'))
    {
        $('#side-menu').hide();
        setTimeout(function() {$('#side-menu').fadeIn(500);}, 300);
    }
    else
        $('#side-menu').removeAttr('style');
}

// Dragable panels
function WinMove()
{
    var element = "[class*=col]";
    var handle = ".ibox-title";
    var connect = "[class*=col]";
    $(element).sortable(
        {
            handle: handle,
            connectWith: connect,
            tolerance: 'pointer',
            forcePlaceholderSize: true,
            opacity: 0.8,
        })
        .disableSelection();
};


