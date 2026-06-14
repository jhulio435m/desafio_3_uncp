import { Pool } from 'pg';
import dotenv from 'dotenv';

dotenv.config();

const pool = new Pool({
  host: process.env.DB_HOST || 'localhost',
  port: parseInt(process.env.DB_PORT || '5432'),
  user: process.env.DB_USER || 'uncp_user',
  password: process.env.DB_PASSWORD || 'uncp_password',
  database: process.env.DB_NAME || 'uncp_proyeccion_social',
});

export const query = (text: string, params?: any[]) => pool.query(text, params);
