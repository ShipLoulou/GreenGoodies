export default class Notification{constructor(){this.selected()}
selected(){const buttons=document.querySelectorAll('.notification div button')
buttons.forEach(item=>{item.addEventListener("click",()=>{const parent=item.parentNode;parent.remove()})})}}