// Seleciona o container da lista de logos
const logosContainer = document.querySelector('.logos ul');

// Duplica o conteúdo da lista para criar um efeito contínuo
const clone = logosContainer.cloneNode(true);
logosContainer.parentElement.appendChild(clone);

// Função para mover o conteúdo para a esquerda
function moveSlider() {
  const totalItems = logosContainer.children.length; // Total de itens na lista
  const itemWidth = logosContainer.children[0].offsetWidth; // Largura de cada item
  const containerWidth = logosContainer.parentElement.offsetWidth; // Largura do container

  // Desloca o conteúdo para a esquerda
  logosContainer.style.transition = 'transform 1s ease'; // Faz a transição suave
  logosContainer.style.transform = `translateX(-${itemWidth}px)`; // Move a lista para a esquerda

  // Quando o primeiro item passar completamente, reinicia a posição
  logosContainer.addEventListener('transitionend', () => {
    // Move o primeiro item para o final
    logosContainer.appendChild(logosContainer.firstElementChild);
    // Reseta a transição para não haver "flicker" e começa de novo
    logosContainer.style.transition = 'none';
    logosContainer.style.transform = 'translateX(0)';
    setTimeout(() => {
      logosContainer.style.transition = 'transform 1s ease'; // Reaplica a transição
    }, 20); // Delay pequeno antes de reiniciar a animação
  });
}

// Inicia o movimento a cada intervalo de 2 segundos
setInterval(moveSlider, 2000); // Muda a posição a cada 2 segundos
