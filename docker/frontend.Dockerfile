FROM node:18-alpine AS builder

RUN apk update \
    && apk upgrade --available \
    && apk add \
      git \
      curl \
      wget \
      bash

RUN mkdir -p /app
WORKDIR /app
RUN git clone https://github.com/hhsnake/test_blog_frontend /app
COPY ./docker/frontend/constants.js /app/src/config/

RUN npm install

RUN npm run build

FROM nginx
COPY --from=builder /app/dist /usr/share/nginx/html

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
