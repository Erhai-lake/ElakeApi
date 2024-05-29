// 页面加载完毕
window.onload = () => {
  // const Content = document.getElementById('Content')
  const PreviousStep = document.getElementById('PreviousStep')
  const NextStep = document.getElementById('NextStep')
  const ContentNum = (document.getElementById('ContentNum'))

  // 上一步
  PreviousStep.addEventListener('click', () => {
    const CurrentStep = Number(ContentNum.textContent);
    switch (Number(ContentNum.textContent)) {
      case 1:
        break
      case 2:
        ToggleStep(CurrentStep, CurrentStep - 1)
        break
      case 3:
        ToggleStep(CurrentStep, CurrentStep - 1)
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
        ToggleStep(CurrentStep, CurrentStep + 1)
        break
      case 3:
        break
    }
  });

  // 切页
  const ToggleStep = (Curren, Next) => {
    const CurrentContent = document.getElementById('Content' + Curren)
    const NextContent = document.getElementById('Content' + Next)
    const CurrentContentItem = document.getElementById('Selected' + Curren)
    const NextContentItem = document.getElementById('Selected' + Next)
    CurrentContent.classList.remove('Selected')
    NextContent.classList.add('Selected')
    CurrentContentItem.classList.remove('Selected')
    NextContentItem.classList.add('Selected')
    ContentNum.textContent = Next
  }
}
