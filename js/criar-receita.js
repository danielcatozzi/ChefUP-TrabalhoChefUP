document.addEventListener('DOMContentLoaded', ()=> {
  const form = document.getElementById('formReceita');
  if(!form) return;
  
  form.addEventListener('submit', async (e)=> {
    e.preventDefault();
    
    // CRUCIAL: Pega o e-mail do usuário logado da sessão
    const userEmail = sessionStorage.getItem('userEmail');
    if (!userEmail) {
        alert('Erro: Usuário não logado. Faça login novamente.');
        window.location.href = 'login.html';
        return;
    }
    
    const btn = form.querySelector('button');
    btn.textContent = 'Salvando...';
    btn.disabled = true;

    // Usamos FormData para lidar com o texto E o arquivo
    const formData = new FormData(form);
    
    // Adiciona o e-mail do autor
    formData.append('autor_email', userEmail); 

    // 1. Envia os dados para o PHP
    try {
        const response = await fetch('php/salvar_receita.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            alert('Receita salva com sucesso!');
            // Redireciona para o painel do especialista
            window.location.href = 'especialista.html'; 
        } else {
            alert('Erro ao salvar: ' + (data.message || 'Desconhecido'));
            btn.textContent = 'Salvar Receita';
            btn.disabled = false;
        }
    } catch (error) {
        console.error('Erro de rede:', error);
        alert('Erro de conexão com o servidor.');
        btn.textContent = 'Salvar Receita';
        btn.disabled = false;
    }
  });
  
});