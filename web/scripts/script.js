$( function() {
    var options = {
            lines: { show: true, lineWidth: 10 },
            points: { show: true, radius: 5 },
            xaxis: {
                mode: "time",
                font: { size: 20 },
                color: '#000000'
            },
            yaxis: {
                font: { size: 20 },
                color: '#000000'
            }
        },
        ajaxRequestStatus = false;

    function ajaxCall() {
        if ( ajaxRequestStatus === true ) {
            return;
        }

        ajaxRequestStatus = true;

        $.get( "/api" + window.location.pathname, function( data ) {
            $.plot( "#chart", data, options );
        } ).always( function() {
            ajaxRequestStatus = false;
        } );
    }

    setTimeout( ajaxCall, 60000*60 );

    ajaxCall();
} );

