FROM nginx:1.26-alpine

# Copier la configuration Nginx
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf

# Exposer le port 80
EXPOSE 80

# Démarrer Nginx
CMD ["nginx", "-g", "daemon off;"]
