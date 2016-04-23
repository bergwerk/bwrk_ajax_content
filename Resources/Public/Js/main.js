/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Georg Dümmler <gd@bergwerk.ag>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 *
 * @author    Georg Dümmler <gd@bergwerk.ag>
 * @package    TYPO3
 * @subpackage    bwrk_ajaxcontent
 ***************************************************************/

$(document).ready(function () {
	$('.tx-bwrk-ajaxcontent-link').click(function () {

		var link = $(this);
		var uid = link.attr('data-pageuid');
		var container = $(this).parents('.tx-bwrk-ajaxcontent-menu');

		var loader = $('body').find('.tx-bwrk-ajaxcontent-loader');

		loader.show();

		if (link.parent('li').hasClass('active')) {
			link.parent('li').removeClass('active');
			$('.tx-bwrk-ajaxcontent-content').fadeOut();

		} else {
			container.find('li').removeClass('active');
			link.parent('li').addClass('active');

			$.ajax({
					method: "POST",
					url: "index.php?eID=bwrkAjaxcontentLoad",
					data: {
						'tx_bwrkajaxcontent_pi3[uid]': uid
					}
				})
				.done(function (content) {

					$('.tx-bwrk-ajaxcontent-content').html(content);

					if (picturefill !== undefined) {
						picturefill();
					}
					$('.tx-bwrk-ajaxcontent-content').fadeIn();
					loader.hide();
				});
		}



		return false;
	});
});


//function dropdown()
//{
//    var ajaxMenu = $('.tx-bwrk-ajaxcontent-menu');
//
//    ajaxMenu.find('.side-nav').each(function ()
//    {
//        var sideNav = $(this);
//
//        sideNav.find('.has-dropdown').each(function ()
//        {
//
//            var hasDropdown = $(this);
//            var link = hasDropdown.find('> a');
//
//            link.click(function () {
//                var wrapper = $(this).parents('.has-dropdown');
//                wrapper.toggleClass('active');
//
//                return false;
//            });
//        });
//    });
//}

