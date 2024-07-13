window.onload = () => {
    const URLPARAMS = new URLSearchParams(window.location.search).get('Url')
    const URL = document.getElementById('Url')
    const PASSWORD = document.getElementById('Password')
    const OPEN = document.getElementById('Open')
    if (URLPARAMS == null) {
        Url.innerText = '链接'
    } else {
        Url.innerText = URLPARAMS
    }
    OPEN.addEventListener('click', () => {
        window.location.href = `https://api.elake.top/ZipBrowse/zip.html?Url=${URLPARAMS}&Password=${PASSWORD.value}`
    })
}
