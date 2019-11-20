// JavaScript Document
/*!

/*!
 * swiper.js
 */
 
window.addEventListener('load', function() {
  var swiper = new Swiper('.swiper-container2', {
    pagination: '.swiper-pagination',
	pagination: '.swiper-pagination2',
    paginationClickable: true,
    nextButton: '.swiper-button-next',
    prevButton: '.swiper-button-prev',
    loop: true,
    slidesPerView: 'auto',
    spaceBetween: 2
  });
}, false);