document.addEventListener('DOMContentLoaded', ()=> {
  const searchInput = document.getElementById('search');
  // Determina se estamos na página especialista (usado para exibir as ações)
  const isEspecialista = window.location.pathname.endsWith('especialista.html');

  // Adiciona listener para a busca
  if (searchInput) {
    searchInput.addEventListener('keyup', (e) => {
      if (e.key === 'Enter') {
        loadRecipes(searchInput.value.trim());
      }
    });
  }

  function loadRecipes(searchTerm) {
    const userEmail = sessionStorage.getItem('userEmail');
    
    let url = `php/listar_receitas.php?search=${encodeURIComponent(searchTerm)}`;
    
    // Filtro para a página do especialista
    if (isEspecialista && userEmail) {
        url += `&userEmail=${encodeURIComponent(userEmail)}`;
    }
    
    fetch(url, {cache: 'no-store'})
      .then(r => r.json())
      .then(data => {
        const list = data.receitas || [];
        
        // Lógica de Redirecionamento de Busca (mantida)
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
    
    // CARROSSEL e GRID (Home Page)
    // ... (Mantida a lógica que usa listagem completa) ...
    
    // TABELAS (CORRIGIDO: Usa a variável isEspecialista para controle)
    document.querySelectorAll('table tbody').forEach(tbody=>{ 
        tbody.innerHTML=''; 
        list.forEach(item=>{ 
            const tr=document.createElement('tr'); 
            
            // Botões de Ação (Aparecem apenas no Painel do Especialista)
            const actionButtons = isEspecialista 
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
                
                ${isEspecialista ? `<td>${actionButtons}</td>` : ''} `; 
            tbody.appendChild(tr); 
        }); 
    });
  }
  // Inicia o carregamento na Home/Especialista
  loadRecipes('');
});