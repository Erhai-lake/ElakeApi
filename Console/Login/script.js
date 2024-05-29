// 页面加载完毕
window.onload = () => {
  const Content = document.getElementById('Content')
  const PreviousStep = document.getElementById('PreviousStep')
  const NextStep = document.getElementById('NextStep')
  const ContentNum = (document.getElementById('ContentNum'))

  // 上一步
  PreviousStep.addEventListener('click', () => {
    const Step1 = document.getElementById('Content' + String(Number(ContentNum.textContent)))
    const Step2 = document.getElementById('Content' + String(Number(ContentNum.textContent) - 1))
    switch (Number(ContentNum.textContent)) {
      case 1:
        break
      case 2:
        Step1.classList.remove('Selected')
        Step2.classList.add('Selected')
        ContentNum.textContent = '1'
        break
    }
  });

  // 下一步
  NextStep.addEventListener('click', () => {
    const CurrentStep = Number(ContentNum.textContent);
    switch (Number(ContentNum.textContent)) {
      case 1:
        ToggleStep(CurrentStep, CurrentStep + 1)
        break
      case 2:
        break
    }
  });

  // 切页
  const ToggleStep = (Curren, Next) => {
    const CurrentContent = document.getElementById('Content' + Curren)
    const NextContent = document.getElementById('Content' + Next)
    CurrentContent.classList.remove('Selected')
    NextContent.classList.add('Selected')
    ContentNum.textContent = Next
  }
}
