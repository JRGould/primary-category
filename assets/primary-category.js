( function() { return {
	$categoryMetabox: null,
	numCategories: 0,
	primaryCategory: 0,

	init: function() {
		this.$categoryMetabox = $('#taxonomy-category');
		this.numCategories = this.$categoryMetabox.find('li').length

		this.updatePrimaryUI();
		
		this.$categoryMetabox.on( 'wpListAddEnd', this.updatePrimaryUI.bind(this) );
		this.$categoryMetabox.on( 'click', 'label.selectit input', this.updatePrimaryUI.bind( this ) );

		this.$categoryMetabox.on( 'click', '.primary-cat-ui button', this.makeItemPrimary.bind( this ) );
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

				if( parseInt( this.primaryCategory ) === catId ) {
					this.primaryCategory = 0;
				}

				return;
			}

			if( ! $ui.length ) {
				// TODO: Translations for these strings
				$catLabel.append( '<span class="primary-cat-ui"><button data-cat-id=' + catId + '>Make Primary</button><span class="affirmative">&nbsp;(Primary)</span></span>' );
			}

		}.bind( this ) );

		if( 0 === this.primaryCategory ) {
			this.updatePrimaryCategoryDisplay();
		}
	},

	makeItemPrimary: function() {
		event.preventDefault();
		let catId = event.target.getAttribute('data-cat-id');
		this.primaryCategory = parseInt( catId );
		this.updatePrimaryCategoryDisplay();
	},


	updatePrimaryCategoryDisplay: function() {
		if( 0 === this.primaryCategory ) {
			let newPrimaryCategory = parseInt( this.$categoryMetabox.find( 'input:checked' ).first().val() );
			if( ! newPrimaryCategory ) {
				return;
			}
			this.primaryCategory = newPrimaryCategory;
		}

		this.$categoryMetabox.find('li').removeClass( 'isPrimary' );

		this.$categoryMetabox.find( 'input[value='+this.primaryCategory+']' )
			.parents( 'li' ).addClass( 'isPrimary' );
	}
	
	
} } )().init( jQuery );