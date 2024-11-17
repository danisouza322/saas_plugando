<?php

return [
    // Mensagens de Sucesso
    'success' => [
        'user_created' => 'Usuário criado com sucesso!',
        'user_updated' => 'Usuário atualizado com sucesso!',
        'user_deleted' => 'Usuário excluído com sucesso!',
        'role_assigned' => 'Função atribuída com sucesso!',
        'certificate_created' => 'Certificado criado com sucesso!',
        'certificate_updated' => 'Certificado atualizado com sucesso!',
        'certificate_deleted' => 'Certificado excluído com sucesso!',
    ],

    // Mensagens de Erro
    'error' => [
        'user_not_found' => 'Usuário não encontrado.',
        'role_not_found' => 'Função não encontrada.',
        'invalid_credentials' => 'Credenciais inválidas.',
        'unauthorized' => 'Você não tem permissão para realizar esta ação.',
        'certificate_not_found' => 'Certificado não encontrado.',
        'invalid_file' => 'Arquivo inválido.',
    ],

    // Títulos e Labels
    'titles' => [
        'dashboard' => 'Painel de Controle',
        'user_management' => 'Gerenciamento de Usuários',
        'certificate_management' => 'Gerenciamento de Certificados',
        'role_management' => 'Gerenciamento de Funções',
        'settings' => 'Configurações',
    ],

    // Botões e Ações
    'actions' => [
        'create' => 'Criar',
        'edit' => 'Editar',
        'delete' => 'Excluir',
        'save' => 'Salvar',
        'cancel' => 'Cancelar',
        'back' => 'Voltar',
        'confirm' => 'Confirmar',
        'upload' => 'Enviar',
        'download' => 'Baixar',
    ],

    // Campos de Formulário
    'fields' => [
        'name' => 'Nome',
        'email' => 'E-mail',
        'password' => 'Senha',
        'role' => 'Função',
        'company' => 'Empresa',
        'status' => 'Status',
        'created_at' => 'Criado em',
        'updated_at' => 'Atualizado em',
        'expires_at' => 'Expira em',
    ],

    // Confirmações
    'confirm' => [
        'delete_user' => 'Tem certeza que deseja excluir este usuário?',
        'delete_certificate' => 'Tem certeza que deseja excluir este certificado?',
        'revoke_access' => 'Tem certeza que deseja revogar o acesso?',
    ],

    // Status
    'status' => [
        'active' => 'Ativo',
        'inactive' => 'Inativo',
        'pending' => 'Pendente',
        'expired' => 'Expirado',
        'revoked' => 'Revogado',
    ],

    // Roles (Funções)
    'roles' => [
        'super-admin' => 'Super Administrador',
        'admin' => 'Administrador',
        'user' => 'Usuário',
        'client' => 'Cliente',
    ],

    // Permissões
    'permissions' => [
        'view-dashboard' => 'Visualizar Painel',
        'manage-users' => 'Gerenciar Usuários',
        'manage-certificates' => 'Gerenciar Certificados',
        'manage-roles' => 'Gerenciar Funções',
        'view-reports' => 'Visualizar Relatórios',
    ],

    // Notificações
    'notifications' => [
        'certificate_expiring' => 'Seu certificado expirará em :days dias.',
        'new_user' => 'Novo usuário registrado: :name',
        'password_changed' => 'Sua senha foi alterada com sucesso.',
    ],
];
