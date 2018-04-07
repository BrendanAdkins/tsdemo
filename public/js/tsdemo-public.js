(function( $ ) {
	'use strict';
	
	var setupStripeHandler = function() {
		// Configure the payment handler on page load in case there is a donation form included
		TSDemoNS.stripeHandler = StripeCheckout.configure({
		  key: php_vars.stripe_api_key,
		  image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
		  locale: 'auto',
		  token: function(token) {
		    console.log("got token id "+token.id);
		    // Get the token ID to your server-side code for use.
		  }
		});
	}
	
	var setupDonationRadioButtons = function() {
		// If we have a set of possible donation amounts from settings, prepend them as radio options
		$.each(php_vars.stripe_amount_options, function(index, value) {
			var template = '<input type="radio" id="tsDemoDonationAmount'+index+'" name="donationAmount" value="'+value+'"';
			if (index == 0) {
				template += ' checked="checked"';
				$('#tsDemoDonationFieldOther').attr('value', value);
				TSDemoNS.amount = value * 100;
			}
			template += '>\n';
			template += '<label for="tsDemoDonationAmount'+index+'">$'+value+'.00</label>';
			$(template).insertBefore("#tsDemoDonationAmountOther");
		});
	}
	
	var setupJQHandlers = function() {
		// Set up a handler for when radio buttons change to update amount
		$('input[type=radio][name=donationAmount]').change(function() {
			
	        if (this.id == 'tsDemoDonationAmountOther') {
		        $("#tsDemoDonationFieldOther").removeAttr("disabled");
            } else {
		        $("#tsDemoDonationFieldOther").attr("value", this.value).attr("disabled", "disabled");
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
	}
	
	// On DOM load, run setup functions
	$(function() {
		
		// reserve a global namespace
		window.TSDemoNS = {}
		
		setupStripeHandler();
		setupDonationRadioButtons();
		setupJQHandlers();
		
		// Close Stripe popup on page navigation
		window.addEventListener('popstate', function() {
		  TSDemoNS.stripeHandler.close();
		});
	});

})( jQuery );
