window.onload = () => {
    const URLPARAMS = new URLSearchParams(window.location.search).get('Url')
    const PASSWORDPARAMS = new URLSearchParams(window.location.search).get('Password')
    const DOWNLOAD = document.querySelector('.Download')
    if (URLPARAMS == null) {
        window.location.href = 'https://api.elake.top/ZipBrowse'
    }
    fetch(`https://api.elake.top/ZipBrowse/ZipJson.php?Url=${URLPARAMS}&Password=${PASSWORDPARAMS}`)
        .then(response => response.json())
        .then(Data => {
            if (Data.Code !== 0) {
                alert(Data.Message)
                window.location.href = 'https://api.elake.top/ZipBrowse'
            }
            document.querySelector('.Dir').appendChild(GenerateUlList(Data.Data))
            DOWNLOAD.addEventListener('click', () => {
                const LINK = document.createElement('a')
                LINK.href = DOWNLOAD.getAttribute('Download')
                LINK.setAttribute('Download', 'true');
                document.body.appendChild(LINK);
                LINK.click();
                document.body.removeChild(LINK);
            })
        })
        .catch(console.error)
}

function GenerateUlList(Data) {
    let Ul = document.createElement('ul')
    Data.Children.forEach(Child => {
        let Li = document.createElement('li')
        let Span = document.createElement('span')
        let IconSpan = document.createElement('span')
        IconSpan.style.marginRight = '5px'

        if (Child.Type === 'directory') {
            if (Child.Children.length > 0) {
                IconSpan.textContent = '▶'
            } else {
                IconSpan.textContent = '▼'
            }
        } else {
            IconSpan.textContent = '●'
            Span.addEventListener('click', function () {
                OpenFile(Child.Name);
            });
        }

        Span.textContent = Child.Name.split('/').pop()
        Li.appendChild(IconSpan)
        Li.appendChild(Span)

        if (Child.Children.length > 0) {
            let NestedUl = GenerateUlList(Child)
            NestedUl.style.display = 'none'
            Span.addEventListener('click', (E) => {
                E.stopPropagation()
                if (NestedUl.style.display === 'none') {
                    NestedUl.style.display = 'block'
                    IconSpan.textContent = '▼'
                } else {
                    NestedUl.style.display = 'none'
                    IconSpan.textContent = '▶'
                }
            })
            Li.appendChild(NestedUl)
        }
        Ul.appendChild(Li)
    })
    return Ul
}

function OpenFile(Del) {
    const IFRAME = document.getElementById('iframe')
    const URL = `https://api.elake.top/ZipBrowse/${Del}`
    const DOWNLOAD = document.querySelector('.Download')
    IFRAME.src = URL
    DOWNLOAD.setAttribute('Download', URL)
}
