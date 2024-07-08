FROM node:20

RUN mkdir /usr/src/app

WORKDIR /usr/src/app

RUN npm install -g @angular/cli

COPY . .

# Add user permission folder
RUN chown -R node:node /usr/src/app

# Switch to non-root user
USER node