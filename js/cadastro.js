document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('cadastroForm');
    
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // 1. Coleta os dados do formulário
        const nome = form.querySelector('input[name="nome"]').value;
        const email = form.querySelector('input[name="email"]').value;
        const senha = form.querySelector('input[name="senha"]').value;

        // 2. Prepara os dados para envio (FormData é o ideal para POST)
        const dados = new FormData();
        dados.append('nome', nome);
        dados.append('email', email);
        dados.append('senha', senha);

        const btn = form.querySelector('button');
        btn.innerHTML = 'Salvando...';
        btn.disabled = true;

        // 3. Envia os dados para o PHP
        try {
            const response = await fetch('php/cadastro.php', {
                method: 'POST',
                body: dados
            });
            const data = await response.json();

            if (data.success) {
                alert('Conta criada com sucesso! Você já pode fazer login.');
                window.location.href = 'login.html';
            } else {
                alert('Erro ao criar conta: ' + data.message);
                btn.innerHTML = 'Cadastrar';
                btn.disabled = false;
            }
        } catch (error) {
            console.error('Erro de rede:', error);
            alert('Erro de conexão com o servidor. Verifique o XAMPP.');
            btn.innerHTML = 'Cadastrar';
            btn.disabled = false;
        }
    });
});