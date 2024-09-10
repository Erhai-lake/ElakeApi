// 获取链接参数
const GetUrlParam = (Name) => {
    let Reg = new RegExp("(^|&)" + Name + "=([^&]*)(&|$)");
    let R = window.location.search.substr(1).match(Reg);
    if (R !== null) {
        return decodeURIComponent(R[2]);
    }
    return null;
};

document.addEventListener('DOMContentLoaded', function () {
    const $SearchInput = $('#SearchInput'),
        $searchSubmit = $('#SearchSubmit'),
        $UrlOutput = $('#UrlOutput'),
        $Tips = $('#Tips'),
        $Arrow = $('#Arrow');

    let stepTimeout, typeInterval;

    let Query = GetUrlParam('Query')
    if (!!Query) {
        try {
            Query = Base64.decode(Query);
        } catch (E) {
            console.log(E);
        }
    }

    if (!!Query) {
        stepTimeout = setTimeout(function () {
            $Tips.html('第一): 找到输入框并选中');
            $Arrow.removeClass('active').show().animate({
                left: $SearchInput.offset().left + 20 + 'px',
                top: ($SearchInput.offset().top + $SearchInput.outerHeight() / 2) + 'px'
            }, 2000, function () {
                $Tips.html('第二): 输入你要找的内容');
                $Arrow.addClass('active');
                stepTimeout = setTimeout(function () {
                    $Arrow.fadeOut();
                    let i = 0;
                    typeInterval = setInterval(function () {
                        $SearchInput.val(Query.substr(0, i));
                        if (++i > Query.length) {
                            clearInterval(typeInterval);
                            $Tips.html('第三): 点击下"Google一下"按钮');
                            $Arrow.removeClass('active').fadeIn().animate({
                                left: $searchSubmit.offset().left + $searchSubmit.width() / 2 + 'px',
                                top: $searchSubmit.offset().top + $searchSubmit.height() / 2 + 'px'
                            }, 1000, function () {
                                $Tips.html('<strong>怎么样,学会了吗?</strong>');
                                $Arrow.addClass('active');
                                stepTimeout = setTimeout(function () {
                                    window.location = 'https://www.google.com/search?q=' + encodeURIComponent(Query);
                                }, 1000);
                            });
                        }
                    }, 200);
                }, 500);
            });
        }, 1000);
    }

    /* 提交 */
    $('#SearchForm').submit(function () {
        if (!!Query) return false;
        let question = $.trim($SearchInput.val());
        if (!question) {
            $Tips.html('<span style="color: red">搜了个寂寞?</span>');
            $SearchInput.val('');
        } else {
            $Tips.html(' 复制下面的链接,甩给伸手党');
            $('#output').fadeIn();
            $UrlOutput.val(window.location.origin + window.location.pathname + '?Query=' + Base64.encode(question)).focus().select();
        }
        return false;
    });

    /* 复制结果 */
    let Clipboard = new ClipboardJS('[data-clipboard-target]');
    Clipboard.on('success', function (e) {
        $Tips.html('<span style="color: #4caf50">复制成功!赶紧把链接甩给伸手党们!</span>');
    });
    Clipboard.on('error', function (e) {
        $Tips.html('<span style="color: red">复制失败,请手动复制...</span>');
    });

    /* 预览 */
    $('#Preview').click(function () {
        let Link = $UrlOutput.val();
        if (!!Link) {
            window.open(Link);
        }
    });
});
