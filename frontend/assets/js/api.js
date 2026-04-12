const API_BASE = "http://localhost/LTWEB/backend_php";

const API_ENDPOINTS = {
  auth: {
    register: `${API_BASE}/api/auth/register`,
    login: `${API_BASE}/api/auth/login`,
    logout: `${API_BASE}/api/auth/logout`,
    me: `${API_BASE}/api/auth/me`,
  },
  categories: {
    publicList: `${API_BASE}/api/categories`,
    adminList: `${API_BASE}/api/admin/categories`,
    create: `${API_BASE}/api/admin/categories`,
    update: (id) => `${API_BASE}/api/admin/categories/${id}`,
    delete: (id) => `${API_BASE}/api/admin/categories/${id}`,
  },
  products: {
    publicList: `${API_BASE}/api/products`,
    publicDetail: (id) => `${API_BASE}/api/products/${id}`,
    adminList: `${API_BASE}/api/admin/products`,
    create: `${API_BASE}/api/admin/products`,
    update: (id) => `${API_BASE}/api/admin/products/${id}`,
    delete: (id) => `${API_BASE}/api/admin/products/${id}`,
    addImages: (id) => `${API_BASE}/api/admin/products/${id}/images`,
    deleteImage: (id) => `${API_BASE}/api/admin/product-images/${id}`,
  },
  cart: {
    current: `${API_BASE}/api/cart`,
    addItem: `${API_BASE}/api/cart/items`,
    updateItem: (id) => `${API_BASE}/api/cart/items/${id}`,
    deleteItem: (id) => `${API_BASE}/api/cart/items/${id}`,
    total: `${API_BASE}/api/cart/total`,
  },
  orders: {
    create: `${API_BASE}/api/orders`,
    history: `${API_BASE}/api/orders`,
    detail: (id) => `${API_BASE}/api/orders/${id}`,
    status: (id) => `${API_BASE}/api/orders/${id}/status`,
    adminList: `${API_BASE}/api/admin/orders`,
    adminDetail: (id) => `${API_BASE}/api/admin/orders/${id}`,
    updateStatus: (id) => `${API_BASE}/api/admin/orders/${id}/status`,
  },
  admin: {
    customers: `${API_BASE}/api/admin/customers`,
    customerDetail: (id) => `${API_BASE}/api/admin/customers/${id}`,
    users: `${API_BASE}/api/admin/users`,
    createUser: `${API_BASE}/api/admin/users`,
    updateUser: (id) => `${API_BASE}/api/admin/users/${id}`,
    deleteUser: (id) => `${API_BASE}/api/admin/users/${id}`,
  }
};

async function apiRequest(url, method = "GET", body = null) {
  const options = {
    method,
    headers: {
      "Content-Type": "application/json"
    }
  };

  if (body) {
    options.body = JSON.stringify(body);
  }

  const response = await fetch(url, options);
  return await response.json();
}