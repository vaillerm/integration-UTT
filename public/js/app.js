var isMobile = {
  Android: function() {
    return navigator.userAgent.match(/Android/i)
  },
  iOS: function() {
    return navigator.userAgent.match(/iPhone|iPad|iPod/i)
  }
}
if (document.location.pathname === '/app') {
  if (isMobile.Android()) {
    document.location.href = 'https://play.google.com/store/apps/details?id=fr.utt.ung.integration&hl=fr_FR'
  } else if (isMobile.iOS()) {
    document.location.href = 'https://apps.apple.com/us/app/int%C3%A9gration-utt/id1403064675?l=fr&ls=1'
  }
}
