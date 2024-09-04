#!/bin/bash
#Destroy docker image
echo "Please wait while service is being destroyed..." \
    && cd public \
    && rm storage \
    && docker compose down -v \
    && echo "All containers destroyed successfully"
