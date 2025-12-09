document.addEventListener('DOMContentLoaded', ()=>{
  const link = document.getElementById('theme-style');
  if(!link) return;
  const saved = localStorage.getItem('tema') || 'styles';
  link.href = saved === 'dark' ? 'css/dark.css' : 'css/styles.css';
});