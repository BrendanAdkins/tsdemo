(function( $ ) {
	'use strict';
	
	var setupStripeHandler = function() {
		// Configure the payment handler on page load in case there is a donation form included
		TSDemoNS.stripeHandler = StripeCheckout.configure({
		  key: php_vars.stripe_api_key,
		  image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
		  locale: 'auto',
		  token: function(token) {
		    $("#tsdemoDonationStripeToken").val(token.id);
		    $("#tsdemoDonationStripeForm").submit();
		  }
		});
	}
	
	var setupDonationRadioButtons = function() {
		// If we have a set of possible donation amounts from settings, prepend them as radio options
		$.each(php_vars.stripe_amount_options, function(index, value) {
			var template = '<li>';
			template += '<input type="radio" id="tsDemoDonationAmount'+index+'" name="donationAmount" value="'+value+'"';
			if (index == 0) {
				template += ' checked="checked"';
				$('#tsDemoDonationFieldOther').attr('value', value);
				$('#tsDemoDonationFieldOther').attr('disabled', 'disabled');
				TSDemoNS.amount = value * 100;
			}
			template += '>\n';
			template += '<label for="tsDemoDonationAmount'+index+'">$'+value+'.00</label>';
			template += '</li>'
			$(template).insertBefore("#tsDemoDonationAmountOtherItem");
		});
	}
	
	var setupFormValues = function() {
		$("#tsdemoDonationStripeForm").attr("action", php_vars.stripe_form_destination);
		$("#tsdemoDonationStripeNonce").val(php_vars.stripe_form_nonce);
	}
	
	var setupJQHandlers = function() {
		// Set up a handler for when radio buttons change to update amount
		$('input[type=radio][name=donationAmount]').change(function() {
			
	        if (this.id == 'tsDemoDonationAmountOther') {
		        $("#tsDemoDonationFieldOther").removeAttr("disabled");
            } else {
		        $("#tsDemoDonationFieldOther").attr("disabled", "disabled").val(this.value);
		        TSDemoNS.amount = this.value * 100;
            }
	    });
	    
	    // Same for when text field is edited
	    $('#tsDemoDonationFieldOther').change(function() {
	        TSDemoNS.amount = this.value * 100;
	    });
		
		// Look for donate form button and override its click
		$('#tsdemoDonateButton').click(function(e) {
		    TSDemoNS.stripeHandler.open({
		    	name: php_vars.stripe_site_name,
				description: 'Donation',
				zipCode: true,
				amount: TSDemoNS.amount
		    });
		    e.preventDefault();
		    return false;
		    // Popup will submit form on completion
		});
		
		// Look for form submission and override it
		$('form').submit(function(e) {

	        var formData = {
	            "wpNonce": $("#tsdemoDonationStripeNonce").val(),
	            "donationToken": $("#tsdemoDonationStripeToken").val(),
	            "action": "tsdemo"
	        };
	
	        // send the request
	        $.ajax({
	            type: 'POST',
	            url: $("#tsdemoDonationStripeForm").attr("action"),
	            data: formData,
	            dataType: 'json',
	            encode: true,
	            beforeSend: function() {
		            $("#tsdemoDonationSpinner .loadingSpinner").html('<img src="'+php_vars.stripe_loader_image+'" alt="Loading...">');
			        $("#tsdemoDonationSpinner").show();
			        $("#tsdemoDonationComplete").hide();
			        $("#tsdemoDonationStripeForm").hide();
			    }
	        }).done(function(data) {
		        console.log("got response from form submission:");
                console.log(data);
                $("#tsdemoDonationSpinner").hide();
		        $("#tsdemoDonationComplete").html(php_vars.thank_you_message).show();
	        }).fail(function() {
                $("#tsdemoDonationSpinner").hide();
		        $("#tsdemoDonationComplete").html(php_vars.uh_oh_message).show();
		        $("#tsdemoDonationStripeForm").show();
	        });
	
	        e.preventDefault();
	    });
	}
	
	// On DOM load, run setup functions
	$(function() {
		
		// reserve a global namespace
		window.TSDemoNS = {}
		
		setupStripeHandler();
		setupFormValues();
		setupDonationRadioButtons();
		setupJQHandlers();
		
		// Close Stripe popup on page navigation
		window.addEventListener('popstate', function() {
		  TSDemoNS.stripeHandler.close();
		});
	});

})( jQuery );
