const HOST = `${window.location.protocol}//${window.location.hostname}${(window.location.port ? ':' + window.location.port : '')}/`
window.onload = () => {
    // 获取参数
    const URLPARAMS = new URLSearchParams(window.location.search).get('Url')
    const PASSWORDPARAMS = new URLSearchParams(window.location.search).get('Password')
    if (URLPARAMS === null || URLPARAMS === '') {
        alert('压缩包链接为空')
        window.location.href = `${HOST}ZipBrowse`
        return
    }
    // 请求API
    const LOADING = document.querySelector('.Loading')
    LOADING.style.display = 'flex'
    fetch(`${HOST}ZipBrowse/ZipJson.php?Url=${URLPARAMS}&Password=${PASSWORDPARAMS}`)
        .then(response => response.json())
        .then(Data => {
            if (Data.Code !== 0) {
                alert(Data.Message)
                window.location.href = `${HOST}ZipBrowse?Url=${URLPARAMS}`
                return
            }
            document.querySelector('.Dir').appendChild(GenerateUlList(Data.Data))
            LOADING.style.display = 'none'
            // 点击下载
            const DOWNLOAD = document.querySelector('.Download')
            DOWNLOAD.addEventListener('click', () => {
                const LINK = document.createElement('a')
                LINK.href = DOWNLOAD.getAttribute('Download')
                LINK.setAttribute('Download', '洱海zip在线浏览器');
                document.body.appendChild(LINK);
                LINK.click();
                document.body.removeChild(LINK);
            })

            // 收缩目录
            const SHRINKAGE = document.querySelector('.Shrinkage')
            SHRINKAGE.addEventListener('click', () => {
                if(document.body.style.gridTemplateColumns === '0px 1fr') {
                    document.body.style.gridTemplateColumns = '250px 1fr'
                    document.body.style.gridTemplateRows = '1fr 100px'
                    SHRINKAGE.textContent = '<'
                } else {
                    document.body.style.gridTemplateColumns = '0 1fr'
                    document.body.style.gridTemplateRows = '1fr 0'
                    SHRINKAGE.textContent = '>'
                }
            })
        })
        .catch(console.error)
}

// 构建列表
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

// 点击文件
function OpenFile(Del) {
    // 获取链接和文件后缀
    const URL = `${HOST}ZipBrowse/${Del}`
    const SUFFIX = URL.split('.').pop().toLowerCase()
    // 注册浏览元素
    const NO = document.querySelector('.Browse .No')
    const IMG = document.querySelector('.Browse .Img')
    const AUDIO = document.querySelector('.Browse .Audio')
    const VIDEO = document.querySelector('.Browse .Video')
    const TEXT = document.querySelector('.Browse .Text')
    const HTML = document.querySelector('.Browse .Html')
    // 设置默认值
    NO.style.display = 'none'
    IMG.style.display = 'none'
    IMG.style.setProperty('--Url', `url()`)
    AUDIO.style.display = 'none'
    AUDIO.src = ''
    VIDEO.style.display = 'none'
    VIDEO.src = ''
    TEXT.style.display = 'none'
    TEXT.innerHTML = ''
    HTML.style.display = 'none'
    HTML.src = ''
    // 浏览格式判断
    switch (SUFFIX) {
        // 图片
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
        case 'svg':
        case 'ico':
        case 'webp':
            IMG.style.setProperty('--Url', `url(${URL})`)
            IMG.style.display = 'block'
            break
        // 音频
        case 'mp3':
        case 'm4a':
        case 'wav':
        case 'opus':
        case 'wma':
        case 'ogg':
        case 'aac':
        case 'flac':
            AUDIO.src = URL
            AUDIO.style.display = 'block'
            break
        // 视频
        case 'mp4':
        case 'mkv':
            VIDEO.src = URL
            VIDEO.style.display = 'block'
            break
        // 文本
        case 'txt':
            fetch(URL)
                .then(Response => Response.text())
                .then(Text => {
                    TEXT.textContent = Text
                })
                .catch(console.error)
            TEXT.style.display = 'block'
            break
        // 页面
        case 'html':
        case 'htm':
            HTML.src = URL
            HTML.style.display = 'block'
            break
        // 超链接
        case 'url':
            fetch(URL)
                .then(Response => Response.text())
                .then(Text => {
                    HTML.src = Text.match(/URL=(\S+)/)[1]
                    HTML.style.display = 'block'
                })
                .catch(console.error)
            break
        // Microsoft
        case 'doc':
        case 'docx':
        case 'xls':
        case 'xlsx':
        case 'ppt':
        case 'pptx':
            HTML.src = `https://view.officeapps.live.com/op/view.aspx?src=${URL}`
            HTML.style.display = 'block'
            break
        // pdf
        case 'pdf':
            HTML.src = `https://alist-org.github.io/pdf.js/web/viewer.html?file=${URL}`
            HTML.style.display = 'block'
            break
        // epub
        case 'epub':
            HTML.src = `https://alist-org.github.io/static/epub.js/viewer.html?url=${URL}`
            HTML.style.display = 'block'
            break
        // zip
        case 'zip':
            HTML.src = `${HOST}ZipBrowse?Url=${URL}`
            HTML.style.display = 'block'
            break
        // 不支持
        default:
            NO.style.display = 'block'
    }
    // 下载链接映射
    const DOWNLOAD = document.querySelector('.Download')
    DOWNLOAD.setAttribute('Download', URL)
}
