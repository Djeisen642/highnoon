/*
 * jQuery Local Storage Plugin v0.3 beta
 */
(function ($) {
  var localStorage = window.localStorage;
  jQuery.support.localStorage = localStorage ? true : false;

  var remove = jQuery.removeLocalStorage = function (key) {
    if (localStorage) localStorage.removeItem(key);
    return;
  };

  function allStorage () {
    return localStorage ? localStorage : undefined;
  }

  var config = jQuery.localStorage = function (key, value) {
    // All Read
    if (arguments.length === 0 ) return allStorage(key);

    // Write
    if (value !== undefined) {
      if (localStorage) localStorage.setItem(key, value);
    }

    // Read
    var result;
    if (localStorage) {
      if (localStorage[key]) result = localStorage.getItem(key);
    }
    return result;
  };

  var io = jQuery.localStorage.io = function (key) {
    return {read : function () {
      return config(key);
    }, write : function (value) {
      return config(key, value);
    }, remove : function () {
      return remove(key);
    }, key : key
    };
  };

})(jQuery);