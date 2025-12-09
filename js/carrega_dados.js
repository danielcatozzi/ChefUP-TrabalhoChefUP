document.addEventListener('DOMContentLoaded', ()=> {
  const searchInput = document.getElementById('search');
  // Determina se estamos na página especialista
  const isEspecialista = window.location.pathname.endsWith('especialista.html');

  loadRecipes('');

  // Adiciona listener para a busca
  if (searchInput) {
    searchInput.addEventListener('keyup', (e) => {
      if (e.key === 'Enter') {
        loadRecipes(searchInput.value.trim());
      }
    });
  }

  function loadRecipes(searchTerm) {
    // Pega o e-mail do usuário logado no navegador
    const userEmail = sessionStorage.getItem('userEmail');
    
    let url = `php/listar_receitas.php?search=${encodeURIComponent(searchTerm)}`;
    
    // FILTRAGEM DE SEGURANÇA: Se for a página especialista, adiciona o filtro de e-mail na URL
    if (isEspecialista && userEmail) {
        url += `&userEmail=${encodeURIComponent(userEmail)}`;
    }
    
    fetch(url, {cache: 'no-store'})
      .then(r => r.json())
      .then(data => {
        const list = data.receitas || [];
        
        // Lógica de Redirecionamento de Busca
        const total = list.length;
        if (searchTerm && total === 1 && !isEspecialista) {
          window.location.href = `receita.html?id=${list[0].id}`;
          return;
        }

        populateAll(list);
      })
      .catch(err => console.error('Erro ao carregar receitas:', err));
  }

  // Função que renderiza os dados
  function populateAll(list){
    
    // CARROSSEL E GRID (Renderizam a lista completa)
    const carousel = document.getElementById('carousel');
    if(carousel){ 
        carousel.innerHTML=''; 
        list.slice(0,6).forEach(item=>{ 
            const c = document.createElement('a'); 
            c.href = `receita.html?id=${item.id}`; 
            c.className = 'card-hero'; 
            c.style.textDecoration = 'none';
            c.innerHTML = `<img src=\"${item.imagem}\" alt=\"${item.nome}\"><div class=\"time-badge\">${item.tempo_forno}</div><div class=\"meta\">${item.nome}</div>`; 
            carousel.appendChild(c); 
        }); 
    }
    
    const grid = document.getElementById('grid');
    if(grid){ 
        grid.innerHTML=''; 
        list.slice(0,8).forEach(item=>{ 
            const card = document.createElement('a'); 
            card.href = `receita.html?id=${item.id}`;
            card.className = 'recipe-card'; 
            card.style.textDecoration = 'none';
            card.innerHTML = `<img class=\"card-img\" src=\"${item.imagem}\" alt=\"${item.nome}\"><div class=\"body\"><div class=\"title\">${item.nome}</div><div class=\"meta\">${item.tipo} <strong>${item.tempo_forno}</strong></div></div>`; 
            grid.appendChild(card); 
        }); 
    }
    
    // TABELAS (Renderizam a lista que JÁ VEM FILTRADA do PHP se for a página especialista)
    document.querySelectorAll('table tbody').forEach(tbody=>{ 
        const needsActions = window.location.pathname.endsWith('especialista.html');

        tbody.innerHTML=''; 
        list.forEach(item=>{ 
            const tr=document.createElement('tr'); 
            
            const actionButtons = needsActions 
                ? `
                  <button onclick="window.editRecipe(${item.id})" class="btn" style="padding: 5px 10px; font-size: 0.8em; margin-right: 5px;">Editar</button>
                  <button onclick="window.deleteRecipe(${item.id})" style="background-color: #b00020; color: white; padding: 5px 10px; font-size: 0.8em; border: none; border-radius: 6px; cursor: pointer;">Excluir</button>
                  `
                : ''; 

            tr.innerHTML=`
                <td>
                    <a href="receita.html?id=${item.id}" style="display: block; text-decoration: none;">
                        <img src="${item.imagem}" style="width:120px;height:80px;object-fit:cover;border-radius:8px">
                    </a>
                </td>
                <td><a href="receita.html?id=${item.id}" style="font-weight:bold; text-decoration: none; color: var(--accent);">${item.nome}</a></td>
                <td>${item.tipo}</td>
                <td>${item.tempo_forno}</td>
                
                ${needsActions ? `<td>${actionButtons}</td>` : ''}
                `; 
            tbody.appendChild(tr); 
        }); 
    });
  }
  loadRecipes('');
});