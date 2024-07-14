const HOST = `${window.location.protocol}//${window.location.hostname}${(window.location.port ? ':' + window.location.port : '')}/`
window.onload = () => {
    const URLPARAMS = new URLSearchParams(window.location.search).get('Url')
    const URL = document.getElementById('Url')
    const PASSWORD = document.getElementById('Password')
    const OPEN = document.getElementById('Open')
    if (URLPARAMS == null) {
        URL.innerText = '链接'
    } else {
        URL.innerText = URLPARAMS
    }
    OPEN.addEventListener('click', () => {
        window.location.href = `${HOST}ZipBrowse/zip.html?Url=${(URLPARAMS === null ? '' : URLPARAMS)}&Password=${PASSWORD.value}`
    })
}
