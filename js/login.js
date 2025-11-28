document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');
    if (!form) return;

    const err = document.getElementById('erro');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = document.getElementById('email').value.trim();
        const senha = document.getElementById('senha').value.trim();

        // 1. Prepara dados para o PHP
        const dados = new FormData();
        dados.append('email', email);
        dados.append('senha', senha);

        const btn = form.querySelector('button');
        btn.innerHTML = 'Verificando...';
        btn.disabled = true;

        // 2. Chama o PHP para verificar o usuário no banco de dados (VERIFICAÇÃO REAL)
        try {
            const response = await fetch('php/login.php', {
                method: 'POST',
                body: dados
            });
            const data = await response.json();

            // 3. Processa a resposta
            if (data.success) {
                // Login bem-sucedido (o banco confirmou)
                sessionStorage.setItem('logado', 'true');
                sessionStorage.setItem('userEmail', email); 
                btn.innerHTML = 'Entrando...';
                setTimeout(() => window.location.href = 'especialista.html', 700);
            } else {
                // Login falhou (senha incorreta no banco)
                err.textContent = data.message || 'E-mail ou senha incorretos.';
                err.style.display = 'block';
                form.classList.remove('shake');
                void form.offsetWidth;
                form.classList.add('shake');
                setTimeout(() => err.style.display = 'none', 2200);
                btn.innerHTML = 'Entrar';
                btn.disabled = false;
            }
        } catch (error) {
            // Falha na conexão com o servidor (XAMPP desligado?)
            console.error('Erro de rede:', error);
            err.textContent = 'Erro de conexão com o servidor. Verifique o XAMPP.';
            err.style.display = 'block';
            btn.innerHTML = 'Entrar';
            btn.disabled = false;
        }
    });
});