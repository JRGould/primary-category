( function() { return {
	$categoryMetabox: null,
	numCategories: 0,
	primaryCategory: parseInt( window.primary_category_data.primary_category ),
	$primaryField: null,

	init: function() {
		this.$categoryMetabox = $('#taxonomy-category');
		this.numCategories = this.$categoryMetabox.find('li').length

		this.addPrimaryField();
		this.updatePrimaryUI();
		this.updatePrimaryCategoryDisplay();
		
		this.$categoryMetabox.on( 'wpListAddEnd', this.updatePrimaryUI.bind(this) );
		this.$categoryMetabox.on( 'change', 'label.selectit input', this.updatePrimaryUI.bind( this ) );
		this.$categoryMetabox.on( 'click', '.primary-cat-ui button', this.makeItemPrimary.bind( this ) );

	},

	setPrimaryCategory: function( primaryCategory ) {
		this.primaryCategory    = parseInt( primaryCategory );
		this.$primaryField.val( this.primaryCategory );
	},

	addPrimaryField: function() {
		this.$primaryField = $('<input type=hidden name=_jrg-primary-category value=0 />');
		this.$categoryMetabox.after( this.$primaryField );
		this.$categoryMetabox.after( '<input type=hidden name=_jrg-primary-category_nonce value="' + window.primary_category_data.nonce + '" />' );
	},

	updatePrimaryUI: function() {
		let $catLabels = this.$categoryMetabox.find( 'label.selectit' );
		$catLabels.toArray().forEach( function( category ) {
			let $catLabel = $( category );
			let $catLi    = $catLabel.parent();
			let $input    = $catLabel.find('input');
			let checked   = $input.prop('checked');
			let catId     = parseInt( $input.val() );
			let $ui       = $catLabel.find( '.primary-cat-ui' );

			if( ! checked ) {
				if( $ui.length ) {
					$ui.remove();
				}

				if( this.primaryCategory === catId ) {
					this.setPrimaryCategory( 0 );
				}

				return;
			}

			if( ! $ui.length ) {
				$catLabel.append( '<span class="primary-cat-ui"><button data-cat-id=' + catId + '>' + window.primary_category_data.strings['make primary'] + '</button><span class="affirmative">&nbsp;(' + window.primary_category_data.strings['primary'] + ')</span></span>' );
			}

		}.bind( this ) );

		if( 0 === this.primaryCategory ) {
			this.updatePrimaryCategoryDisplay();
		}
	},

	makeItemPrimary: function() {
		event.preventDefault();
		let catId = event.target.getAttribute('data-cat-id');
		this.setPrimaryCategory( catId );
		this.updatePrimaryCategoryDisplay();
	},

	updatePrimaryCategoryDisplay: function() {
		if( 0 === this.primaryCategory ) {
			let newPrimaryCategory = parseInt( this.$categoryMetabox.find( 'input:checked' ).first().val() );
			if( ! newPrimaryCategory ) {
				return;
			}
			this.setPrimaryCategory( newPrimaryCategory );
		}

		this.$categoryMetabox.find('li').removeClass( 'isPrimary' );

		this.$categoryMetabox.find( 'input[value='+this.primaryCategory+']' )
			.parents( 'li' ).addClass( 'isPrimary' );
	}
	
	
} } )().init( jQuery );
