FROM ubuntu:latest

MAINTAINER Filis Futsarov "filisfutsarov@gmail.com"

ARG user
ENV USER $user
ARG gid
ENV GID $gid
ARG uid
ENV UID $uid

RUN apt-get update && \
    apt-get --no-install-recommends -q -y install apt-utils curl wget libzip-dev libicu-dev libpng-dev git zip less mariadb-client ca-certificates apt-transport-https software-properties-common lsb-release sudo

# this is mainly to a avoid Timezone questions when installing PHP
RUN echo 'debconf debconf/frontend select Noninteractive' | debconf-set-selections

RUN mkdir -p /opt/src/scripts/

COPY install_php.sh /opt/src/scripts/setup.sh
RUN ["/opt/src/scripts/setup.sh"]

RUN mkdir -p "/home/$USER"

# COMPOSER
RUN mkdir -p "/home/$USER/bin"
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir="/home/$USER/bin" --filename=composer
ENV PATH "/home/$USER/bin":$PATH

RUN cp /root/.bashrc /home/$USER/
RUN chown -R $UID:$GID "/home/$USER"

#    && rm -rf /var/lib/apt/lists/*

# this is mainly to be able to switch PHP versions (update-alternatives)
RUN adduser $USER && usermod -aG sudo $USER && echo "$USER:$USER" | chpasswd
RUN echo "$USER	ALL=(ALL:ALL) NOPASSWD:ALL" >> /etc/sudoers

